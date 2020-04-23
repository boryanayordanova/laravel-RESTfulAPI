<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only users with verification can be there, otherway they will be redirected to the login page.
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // executed every time, when an user loged in sucessfully in the system. 
    public function index()
    {
        return view('home');
    }
}
