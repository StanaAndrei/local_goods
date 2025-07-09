<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = ['seller_id', 'buyer_id', 'product_id', 'quantity', 'unit', 'cost'];
}
