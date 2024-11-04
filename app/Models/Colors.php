<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colors extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'color_name',
        'color_code',
        'created_by',
        'updated_by',
    ];

}