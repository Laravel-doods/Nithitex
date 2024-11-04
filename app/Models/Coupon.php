<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_name',
        'coupon_code',
        'color_code',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_common',
        'is_active'
    ];
}
