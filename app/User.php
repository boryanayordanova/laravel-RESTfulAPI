<?php

namespace App;

use Illuminate\Support\Str;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';


    protected $table = 'users';
    protected $dates = ['deleted_at'];

    public $transformer = UserTransformer::class;
    //mutator
    public function setNameAttribute($name){
        $this->attributes['name'] = strtolower($name);
    }
    //accessor
    public function getNameAttribute($name){
        return ucwords($name);
    }

    public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'is_verified',
        'verification_token',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function verify($token)
    // {
    //     $user = User::where('verification_token', $token)->firstOrFail();

    //     $user->verified = User::VERIFIED_USER;
    //     $user->verification_token = null;

    //     $user->save();

    //     return $this->showMessage('The account has been verified succesfully');
    // }

    public function isVerified(){
        return $this->is_verified == User::VERIFIED_USER;
    }

    public function isAdmin(){
        return $this->is_admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return Str::random(40);
    }
}
