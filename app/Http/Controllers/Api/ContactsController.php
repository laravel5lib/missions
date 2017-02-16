<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\v1\ContactRequest;
use App\Http\Transformers\v1\ContactTransformer;
use App\Models\v1\User;

class ContactsController extends Controller
{

    /**
     * ContactsController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('api.auth');
//        $this->middleware('jwt.refresh');
    }

    /**
     * Get all the user's contacts.
     *
     * @param $userId
     * @return \Dingo\Api\Http\Response
     */
    public function index($userId)
    {
        $user = $this->user->findOrFail($userId);

        $contacts = $user->contacts()->paginate(25);

        return $this->response->paginator($contacts, new ContactTransformer);
    }

    /**
     * Get the user's specified contact.
     *
     * @param $userId
     * @param $contactId
     * @return \Dingo\Api\Http\Response
     */
    public function show($userId, $contactId)
    {
        $user = $this->user->findOrFail($userId);

        $contact = $user->contacts()->findOrFail($contactId);

        return $this->response->item($contact, new ContactTransformer);
    }

    /**
     * Create a new contact and save in storage.
     *
     * @param ContactRequest $request
     * @param $userId
     * @return \Dingo\Api\Http\Response
     */
    public function store(ContactRequest $request, $userId)
    {
        $user = $this->user->findOrFail($userId);

        $contact = $user->contacts()->create($request->all());

        $location = url('/users/' . $userId . '/contacts/' . $contact->id);

        return $this->response->created($location);
    }

    /**
     * Update the user's specified contact in storage.
     *
     * @param ContactRequest $request
     * @param $userId
     * @param $contactId
     * @return \Dingo\Api\Http\Response
     */
    public function update(ContactRequest $request, $userId, $contactId)
    {
        $user = $this->user->findOrFail($userId);

        $contact = $user->contacts()->findOrFail($contactId);

        $contact->update($request->all());

        return $this->response->item($contact, new ContactTransformer);
    }

    /**
     * Remove the user's specified contact from storage.
     *
     * @param $userId
     * @param $contactId
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($userId, $contactId)
    {
        $user = $this->user->findOrFail($userId);

        $contact = $user->contacts()->findOrFail($contactId);

        $contact->delete();

        return $this->response->noContent();
    }
}
