<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'main_category_name',
        'main_category_image',
        'created_by',
        'updated_by',
    ];

    public function categories(){
        return $this->hasMany(Category::class, 'main_category_id', 'id');
    }
}
