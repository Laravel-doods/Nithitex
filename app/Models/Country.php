<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'sort_name',
        'country_name',
        'phone_code',
        'currency',
        'currency_symbol',
        'currency_name'
    ];
}
