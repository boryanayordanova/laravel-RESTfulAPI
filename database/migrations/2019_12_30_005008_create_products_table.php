<?php

use App\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            //$table->bigIncrements('id');
            $table->increments('id');
            $table->timestamps();
            //additions            
            $table->string('name'); 
            $table->string('description', 1000); //225 characters for string by default, so we need more here            
            $table->integer('quantity')->unsigned(); //cannot be negative
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('image');
            $table->integer('seller_id')->unsigned();//cannot be negative
            $table->softDeletes(); //deleted_at
            
            //foreign key            
            //$table->foreign('seller_id')->references('id')->on('users'); //no migration for seller, so we use users, Seller extends User
        });
        
        //foreign key  
        Schema::table('products', function($table) {
            $table->foreign('seller_id')->references('id')->on('users')/*->onDelete('cascade')*/;  
                   
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
