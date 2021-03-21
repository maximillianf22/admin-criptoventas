<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cupones extends Model
{
    protected $table = 'coupons';
    protected $fillable = ['name', 'value', 'min_shopping', 'max_quantity', 'state'];

    public function getCommerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }

    public function getCoupons()
    {
        return $this->belongsTo('App\cupones', 'commerce_id');
    }
}