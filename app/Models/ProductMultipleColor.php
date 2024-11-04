<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMultipleColor extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'product_id',
        'color_id',
        'product_price',
        'current_stock',
        'product_image',
        'created_by',
        'updated_by',
    ];
}
