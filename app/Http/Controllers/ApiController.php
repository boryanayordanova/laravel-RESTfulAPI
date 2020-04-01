<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{   
    //all otgher controllers extends ApicOntroller, so the functions here, can be called directly on the Controllers.
    //The trait with the diff responses also can be acces on the other Controlles by usage it here:
    use ApiResponser; //inport class
    
    public function __construct(){
      
    }
}
