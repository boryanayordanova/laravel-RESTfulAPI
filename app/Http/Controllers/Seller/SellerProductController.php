<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller) 
    /*if we use Seller here, Seller is user, who already has a product, 
    but if a user is trying to pusblish at first time his product it's not gonna be posible*/
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        //$data['image'] = '1.jpg';//static image
        $data['image'] = $request->image->store('');//image from the request with params path and images
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
        //return response()->json(['data' => $product], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'image' => 'image',
        ];

        $this->validate($request, $rules);

        //we need to verify if the seller who we were retrieve on the request in the url basically is the owner of this product
        $this->checkSeller($seller, $product);

        //we need to know if this seller is the real seller or the owner of the product
        //intersect/only method are used to ignore the null or empty values
        $product->fill($request->only([ //only() if we are using laravel 5.5
            'name',
            'description',
            'quantity',
        ])); 

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }
        
        if($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        //if something change, isClean() means nothing change
        if ($product->isClean()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }   

        //else something was changed:    
        $product->save();

        return $this->showOne($product);
    }

    protected function checkSeller(Seller $seller, Product $product){
        if($seller->id != $product->seller_id){
            //to update we need to specify the same seller in the request and from the produst
            throw new HttpException(422, "The specified seller is not actual seller of this product");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->delete(); //remove the instanse

        //remove the file. The delete method recieves the name of the file, relatively from the root folder of the image system that we have. 
        //It means public/img folder.  
        Storage::delete($product->image); //we need the specify the name of the file that is basecally the value of the image attribute of the product.
        //we are using softdelete method, that means the product still existing on the db, so we dont remove the image file compleatly.

        return $this->showOne($product);
    }


}
