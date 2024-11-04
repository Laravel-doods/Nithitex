<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement',
        'address_line_1',
        'address_line_2',
        'pincode',
        'mobile_number',
        'email',
        'contact_image',
        'andriod_link',
        'ios_link',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
    ];
}
