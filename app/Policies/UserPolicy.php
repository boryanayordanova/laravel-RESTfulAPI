<?php

namespace App\Policies;

use App\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminActions;

    // /**
    //  * Determine whether the user can view any models.
    //  *
    //  * @param  \App\User  $user
    //  * @return mixed
    //  */
    // public function viewAny(User $user)
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //the user is the authenticated user, the model is the regurlar user
        //the user that currently is sending the access_token and the user that is going to validate the access
        return $user->id === $model->id;
    }

    // /**
    //  * Determine whether the user can create models.
    //  *
    //  * @param  \App\User  $user
    //  * @return mixed
    //  */
    // public function create(User $user)
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //the user is the authenticated user, the model is the regurlar user
        //the user that currently is sending the access_token and the user that is going to validate the access
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //the $user is the authenticated user, the $model is the regurlar user
        //the user that currently is sending the access_token and the user that is going to validate the access
        //return $user->id === $model->id;

        //we need to validate if the access_token is a personal access_token and if this client is a personal client, or not
        //if the personal_access_client is true, that means this client is a personal_access_client, which means this token is a personal_access_token
       
        return $user->id === $model->id && $user->token()->client->personal_access_client;
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\User  $model
    //  * @return mixed
    //  */
    // public function restore(User $user, User $model)
    // {
    //     //the user is the authenticated user, the model is the regurlar user
    //     //the user that currently is sending the access_token and the user that is going to validate the access
    //     return $user->id === $model->id;
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  *
    //  * @param  \App\User  $user
    //  * @param  \App\User  $model
    //  * @return mixed
    //  */
    // public function forceDelete(User $user, User $model)
    // {
    //     //the user is the authenticated user, the model is the regurlar user
    //     //the user that currently is sending the access_token and the user that is going to validate the access
    //     return $user->id === $model->id;
    // }
}
