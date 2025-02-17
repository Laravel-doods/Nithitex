<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
    	return $this->belongsTo(Product::class,'product_id','id');
    }
    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariants::class, 'variant_id');
    }
}
