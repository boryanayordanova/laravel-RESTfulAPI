<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// we need to replace Auth::routes(); with the routes generated from auth.
// we can get them from:
//D:\_Projects\LARAVEL\RESTfulAPI\vendor\laravel\framework\src\Illuminate\Routing\Router.php

// ORIGINAL:
// Auth::routes();

// REPLACED:
//$this-> changed to "Route::"
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
// // Password Reset Routes... 
// Route::resetPassword();
// // Password Confirmation Routes...   
// Route::prependGroupNamespace('Auth\ConfirmPasswordController');
// Route::confirmPassword();
// // Email Verification Routes...        
// Route::emailVerification();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function(){
    return view('welcome');
})->middleware('guest'); 
// guest- only allowed and not validated to be there, otherway they gonna be redirected to the home route