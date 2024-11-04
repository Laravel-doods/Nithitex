<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'main_category_id',
        'category_name',
        'category_slug',
        'category_code',
        'category_image',
        'category_description',
        'is_today_offer',
        'offer',
        'created_by',
        'updated_by',
    ];

    public function main_category(){
    	return $this->belongsTo(MainCategory::class,'main_category_id','id');
    }
}