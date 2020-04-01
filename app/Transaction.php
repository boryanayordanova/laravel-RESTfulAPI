<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'buyer_id', //belongsTo(Buyer)
        'product_id', //belongsTo(Product)
    ];
    protected $dates = ['deleted_at'];


    public $transformer = TransactionTransformer::class;
    
    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
