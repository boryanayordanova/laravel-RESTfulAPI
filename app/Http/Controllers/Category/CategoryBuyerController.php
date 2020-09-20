<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $this->allowedAdminAction();
        
        $buyers = $category->products()
            ->whereHas('transactions') //only the products that already has transactions
            ->with('transactions.buyer')  //obatain the transactions with buyer
            ->get()
            ->pluck('transactions') //remove 'transaction' level as well
            ->collapse() //unique transaction list
            ->pluck('buyer') //only the buyer of full list of transactions
            ->unique('id') //if we have repeated buyer
            ->values(); //remove the empty values
       
        return $this->showAll($buyers);     
    }
    
}
