<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commercesCategories extends Model
{
    protected $table = 'commerce_categories';
    protected $fillable = [
        'name',
        'photo',
        'description',
        'parent',
        'commerces_id',
        'commerce_type'
    ];
    public function getCommerces()
    {
        return $this->belongsToMany('App\Commerce', 'commerce_categories_asociations', "commerce_category_id", "commerce_id")->where('commerces.state', 1);
    }
}
