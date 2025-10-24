<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image', 'category_id'];
    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }   
}
