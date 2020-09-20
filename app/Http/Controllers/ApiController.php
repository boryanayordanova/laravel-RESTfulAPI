<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller
{   
    //all otgher controllers extends ApicOntroller, so the functions here, can be called directly on the Controllers.
    //The trait with the diff responses also can be acces on the other Controlles by usage it here:
    use ApiResponser; //inport class
    
    public function __construct(){

        $this->middleware('auth:api');
      
    }

    protected function allowedAdminAction(){
        //denies works as well
        if(Gate::denies('admin-action')){
            throw new AuthorizationException("This action is unauthorized");
        }
    }
}
