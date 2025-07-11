<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = ['buyer_id', 'product_id', 'quantity', 'cost'];

    protected $casts = [
        'quantity' => 'float',
        'cost' => 'float',
    ];

    public function seller()
    {
        // Access seller through product
        return $this->product->seller();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
