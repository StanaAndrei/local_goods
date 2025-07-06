<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Subcategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'category', 'subcategory', 'name', 'description',
        'quantity', 'unit', 'price', 'additional_info', 'is_public',
    ];

    protected $casts = [
        'additional_info' => 'json',
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
