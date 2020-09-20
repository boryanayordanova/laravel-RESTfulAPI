<?php

namespace App\Policies;

use App\User;
use App\Product;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization, AdminActions;

    

    /**
     * Determine whether the user can add the product category.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function addCategory(User $user, Product $product)
    {
        //we need to check if the current user is a seller
        return $user->id === $product->seller->id;
    }

   
    /**
     * Determine whether the user can permanently delete the product category.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function deleteCategory(User $user, Product $product)
    {
        //we need to check if the current user is a seller
        return $user->id === $product->seller->id;
    }
}
