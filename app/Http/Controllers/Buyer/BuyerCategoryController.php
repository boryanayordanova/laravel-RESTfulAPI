<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse()  //creates a unique list with several lists insede. May have repeated categories
        ->unique('id')  //remove the repeated categories, and left just those with unique id's
        ->values(); //remove the empty categories when repeated
            
    return $this->showAll($categories);  
    }   
}
