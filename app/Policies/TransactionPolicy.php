<?php

namespace App\Policies;

use App\User;
use App\Transaction;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, AdminActions;

    // /**
    //  * Determine whether the user can view any transactions.
    //  *
    //  * @param  \App\User  $user
    //  * @return mixed
    //  */
    // public function viewAny(User $user)
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Transaction  $transaction
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        //the user is a seller or a buyer
        //if one of the conditions is true we can provide access
        return $user->id === $transaction->buyer->id || $user->id === $transaction->product->seller->id;

    }

    // /**
    //  * Determine whether the user can create transactions.
    //  *
    //  * @param  \App\User  $user
    //  * @return mixed
    //  */
    // public function create(User $user)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can update the transaction.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\Transaction  $transaction
    //  * @return mixed
    //  */
    // public function update(User $user, Transaction $transaction)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can delete the transaction.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\Transaction  $transaction
    //  * @return mixed
    //  */
    // public function delete(User $user, Transaction $transaction)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can restore the transaction.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\Transaction  $transaction
    //  * @return mixed
    //  */
    // public function restore(User $user, Transaction $transaction)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the transaction.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\Transaction  $transaction
    //  * @return mixed
    //  */
    // public function forceDelete(User $user, Transaction $transaction)
    // {
    //     //
    // }
}
