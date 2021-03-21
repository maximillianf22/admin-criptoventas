<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketProduct extends Model
{
    protected $table = 'market_products';

    public function getProductVariations()
    {
        return $this->hasMany(MarketProduct::class, 'parent', 'product_id')->whereIn('state', [0, 1]);
    }

    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getParent()
    {
        return $this->belongsTo(Product::class, 'parent');
    }
}
