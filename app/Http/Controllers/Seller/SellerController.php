<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellers = Seller::has('products')->get();
        //return response()->json(['data' => $sellers], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showAll($sellers);
        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller/*$id*/)
    {
        //$seller = Seller::has('products')->findOrFail($id); //if the param is $id

        //return response()->json(['data' => $seller], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($seller);
    }

}
