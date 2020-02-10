<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
            //all the products has relationship "ransactions()", but we dont know if all the products has a transaction, some of them may not have

            //whereHas record    
            ->whereHas('transactions')  //only the products that have at least one transaction            
            //with relationship
            ->with('transactions')  //all the transactions including those products that do not have any transactions:
            ->get()
            ->pluck('transactions')
            ->collapse(); //creates a unique list with several lists inside. May have repeated transactions

       return $this->showAll($transactions);     
    }   
}
