<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\v1\User;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\UserRequest;
use App\Http\Transformers\v1\UserTransformer;
use App\Http\Requests\v1\ExportRequest;
use App\Jobs\ExportUsers;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\UserListImport;

class UsersController extends Controller
{

    /**
     * Instantiate a new UsersController instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get a list of users
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->user
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 25));

        return $this->response->paginator($users, new UserTransformer);
    }

    /**
     * Get a single user
     *
     * @param null $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id = null)
    {
        if($id)
        {
            $user = $this->user->findOrFail($id);
        }
        else
        {
            $user = $this->auth->user();
            if(! $user) return $this->response->errorUnauthorized();
        }

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Create a new user and save in storage
     *
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->except('url'));
        $user->save();

        $user->slug()->create([
            'url' => $request->get('url', generate_slug( $request->get('name') ))
        ]);

        dispatch(new SendWelcomeEmail($user));

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Update a user in storage
     *
     * @param UserRequest $request
     * @param null $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UserRequest $request, $id = null)
    {
        if($id)
            $user = $this->user->findOrFail($id);
        else
            $user = $this->auth()->user();

        $user->update($request->except('url'));

        if ($request->has('links')) {
            $user->syncLinks($request->get('links'));
        }

        $user->slug()->update(['url' => $request->get('url', $user->slug->url)]);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Delete a user
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);

        $user->delete();

        return $this->response->noContent();
    }

    /**
     * Export users.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportUsers($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of users.
     * 
     * @param  UserListImport $import
     * @return response
     */
    public function import(ImportRequest $request, UserListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
