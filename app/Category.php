<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'img_category',
        'description',
        'parent',
        'commerce_id',
    ];

    public function getCommerce()
    {
        return $this->belongsTo(Commerce::class, 'commerce_id');
    }

    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::class, 'category_id');
    }
    public function getReletedProducts()
    {
        return $this->belongsToMany(Product::class,'product_categories','category_id','product_id');
    }
    public function getCommerces()
    {
        return $this->BelongsToMany('App\Commerce', 'commerces_categories', 'id_category', 'id_commerce');
    }
    public function getCategorySons()
    {
        return $this->hasMany(Category::class, 'parent');
    }
}
