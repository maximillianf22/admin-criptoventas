<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commerce extends Model
{
    protected $fillable = [
        'user_id',
        'bussiness_name',
        'nit',
        'commerce_type_vp',
        'is_opened',
        'delivery_config',
        'delivery_value',
        'state'
    ];
    protected $table = 'commerces';

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getCommerceType()
    {
        return $this->belongsTo(ParameterValue::class, 'commerce_type_vp');
    }
    public function getCategories()
    {
        return $this->belongsToMany('App\commercesCategories', 'commerce_categories_asociations',  "commerce_id", "commerce_category_id");
    }
    public function getSliders()
    {
        return $this->hasMany('App\Slider', 'commerce_id');
    }
    public function getMinList()
    {
        return $this->hasMany(ProfileMin::class, 'commerce_id');
    }
    public function getProducts()
    {
        return $this->hasManyThrough(ProductCategory::class,Category::class,'commerce_id','category_id')->groupBy('product_id');
    }
    public function getComerce()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getCoupons()
    {
        return $this->hasMany(cupones::class, 'commerce_id');
    }
}