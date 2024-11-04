<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'product_id',
        'name',
        'price',
        'qty',
        'total',
        'image',
        'variant_id',
    ];
}
