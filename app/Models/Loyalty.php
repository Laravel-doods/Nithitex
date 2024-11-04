<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'loyalty_rate',
        'type',
        'earn_per_order',
        'max_redeem_per_order'
    ];
}
