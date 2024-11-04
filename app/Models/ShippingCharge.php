<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipping_payment_method',
        'shipping_amount',
    ];
}
