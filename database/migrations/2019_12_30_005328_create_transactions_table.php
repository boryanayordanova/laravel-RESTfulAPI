<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->timestamps();
            //additions
            $table->integer('quantity')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->softDeletes(); //deleted_at
            
            //foreign keys
            //$table->foreign('buyer_id')->references('id')->on('users'); //no migration for buyers, so we use users, Buyer extends User
            //$table->foreign('product_id')->references('id')->on('products');
        });
        
        //foreign keys
        Schema::table('transactions', function($table) {
            $table->foreign('buyer_id')->references('id')->on('users')/*->onDelete('cascade')*/;
            $table->foreign('product_id')->references('id')->on('products')/*->onDelete('cascade')*/;         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
