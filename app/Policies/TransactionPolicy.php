<?php

namespace App\Policies;

use App\Models\v1\Fund;
use App\Models\v1\User;
use App\Models\v1\Transaction;

class TransactionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the transaction.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->can('view', request()->route('fund'))) {
            return true;
        }

        return $user->can('view_transactions');
    }

    /**
     * Determine whether the user can create transactions.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_credit_card_transactions');
    }

    /**
     * Determine whether the user can update the transaction.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_transactions');
    }

    /**
     * Determine whether the user can delete the transaction.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_transactions');
    }
}
