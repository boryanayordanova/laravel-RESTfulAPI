<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    public function __construct(){
        parent::__construct();
        //we dont implement here the middlewhare for scope:read-general because this should be accesseble only the the admins. We shouldnt give any other permissions.
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {

        $this->allowedAdminAction();
        
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')   //seller is in product on json reslt
            ->unique('id')  //cannot ise repeated sellers
            ->values(); //if there exist a repeated seller, 
                        //the unique method is going to remove this from the collectin,
                        //but there is going to have empty space, we we are going to have diff sellers, and we are going to have an emty object
        
        return $this->showAll($sellers);            
    }

   
}
