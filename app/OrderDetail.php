<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    public function getProduct()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
    public function getOrder()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
