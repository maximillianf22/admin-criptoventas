<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $table = 'price_lists';

    public function getProduct()
    {
        return $this->belongsTo(Product::class,'products_id');
    }
    public function getProfile()
    {
        return $this->belongsTo(Rol::class,'profile_vp', 'id');
    }

}