<?php
namespace App\Traits;

trait AdminActions{

       //$user is the current authenticated user, ability is the action he can do (view, purchase)
       public function before($user, $ability){
        //if the before method returns true, laravel is going to alow the access to the action, protected by this policy
        //if the before method returns false, the method will deny the access for this specifis action

        //we want to return true, if the use is an administrator
        if($user->isAdmin()){
            return true;
        }

    }

}