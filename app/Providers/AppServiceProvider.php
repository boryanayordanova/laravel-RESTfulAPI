<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        //import User, Mail, UserCreated
        User::created(function($user){
            
            retry(5, function() use ($user){
                Mail::to($user)->send(new UserCreated($user));
            }, 100);    
        });

        //import User, Mail, UserMailChanged
        User::updated(function($user){
            //before we sent the email we hava to be sure that the user email changed:
                if($user->isDirty('email')){ //if we don't send any paramenters on inDirty function, it's gonna verified every attribute for this instance, but if we specify exatly one it will verify only this one -> email
                    //if email changed (isDirty is true)

                    retry(5, function() use ($user){
                        Mail::to($user)->send(new UserMailChanged($user));
                    }, 100);

                }
            
        });

        Product::updated(function($product){
            if($product->quantity == 0 && $product->isAvailable()){
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        });


    }
}
