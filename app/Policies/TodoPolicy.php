<?php

namespace App\Policies;

use App\Models\v1\User;

class TodoPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the todo.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_tasks');
    }

    /**
     * Determine whether the user can create todos.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_tasks');
    }

    /**
     * Determine whether the user can update the todo.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_tasks');
    }

    /**
     * Determine whether the user can delete the todo.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_tasks');
    }
}
