<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Subcategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'seller_id', 'category', 'subcategory', 'name', 'description',
        'quantity', 'unit', 'price', 'additional_info', 'is_public',
    ];

    protected $casts = [
        'additional_info' => 'array',
        'category' => Category::class,
        'subcategory' => Subcategory::class,
        'is_public' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
