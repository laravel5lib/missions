<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\User;
use App\Jobs\ExportUsers;
use App\Jobs\SendWelcomeEmail;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\UserRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\UserListImport;
use App\Http\Transformers\v1\UserTransformer;

class UsersController extends Controller
{

    /**
     * Instantiate a new UsersController instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('auth:api');
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
        if ($id) {
            $user = $this->user->findOrFail($id);
        } else {
            $user = $this->auth->user();
            if (! $user) {
                return $this->response->errorUnauthorized();
            }
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
        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user = new User($request->except('url'));
        $user->save();

        $user->slug()->create([
            'url' => $request->filled('url') ? $request->input('url') : generate_slug($request->input('first_name'))
        ]);

        if ($request->filled('links')) {
            $user->syncLinks($request->input('links'));
        }

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
        if ($id) {
            $user = $this->user->findOrFail($id);
        } else {
            $user = $this->auth()->user();
        }

        if ($request->filled('password')) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }

        $user->update($request->except('url'));

        if ($request->filled('links')) {
            $user->syncLinks($request->input('links'));
        }

        $user->slug()->update(['url' => $request->input('url', $user->slug->url)]);

        // immedately update on user object
        $user->slug->url = $request->input('url', $user->slug->url);

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
