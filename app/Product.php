<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function getMarketProduct()
    {
        return $this->hasOne(MarketProduct::class, 'product_id', 'id');
    }

    public function getRestaurantProduct()
    {
        return $this->hasOne(RestaurantProduct::class, 'product_id', 'id');
    }

    public function getValues()
    {
        return $this->hasMany(PriceList::class, 'products_id', 'id');
    }
    public function get_Values()
    {
        return $this->belongsToMany(PriceList::class, 'products_id', 'id');
    }

    public function getCategories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }

    public function getRealatedCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function getPriceList()
    {
        return $this->belongsToMany(Rol::class, 'price_lists', 'products_id', 'profile_vp')->withPivot('profile_vp', 'value');
    }

    public function getCurrentPriceList()
    {
        return $this->hasMany(PriceList::class, 'products_id');
    }
}
