<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{

    public function __construct(){
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,buyer')->only('show');                  
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->allowedAdminAction();

        $buyers = Buyer::has('transactions')->get();
        //return response()->json(['data' => $buyers], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showAll($buyers);
    }

    
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer/*$id*/)
    {
        //$buyer = Buyer::has('transactions')->findOrFail($id); //used when the param is $id;
        //return response()->json(['data' => $buyer], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($buyer);
        
    }
  
}
