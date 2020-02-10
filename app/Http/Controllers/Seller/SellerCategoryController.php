<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $categories = $seller->products()
            ->whereHas('categories') //if exist any product record without category 
            ->with('categories')    //with category relationship
            ->get() //obtain the results
            ->pluck('categories') //creates only a collection with categories
            ->collapse() //a product can have several categories, we will have a collection with several collections insede
            ->unique('id')//withouth repeated categories
            ->values(); //without empty fields

        return $this->showAll($categories);
    }    
}
