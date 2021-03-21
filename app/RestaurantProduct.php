<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantProduct extends Model
{
    protected $table = 'restaurant_products';

    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
