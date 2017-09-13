<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Note;

class NotePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the note.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Note  $note
     * @return mixed
     */
    public function view(User $user, Note $note = null)
    {
        return $user->can('view_notes');
    }

    /**
     * Determine whether the user can create notes.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_notes');
    }

    /**
     * Determine whether the user can update the note.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Note  $note
     * @return mixed
     */
    public function update(User $user, Note $note = null)
    {
        return $user->can('edit_notes');
    }

    /**
     * Determine whether the user can delete the note.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Note  $note
     * @return mixed
     */
    public function delete(User $user, Note $note = null)
    {
        return $user->can('delete_notes');
    }
}
