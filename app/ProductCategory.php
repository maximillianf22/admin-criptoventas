<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    public function getCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function getActiveProduct()
    {
        return $this->belongsTo(Product::class, 'product_id')->where('state',1);
    }
}
