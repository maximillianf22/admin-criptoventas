<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['state'];

    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function getCustomer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function getCommerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }
    public function getAddress()
    {
        return $this->belongsTo('App\UserAddress', 'user_address_id');
    }
    public function getComission()
    {
        return $this->hasOne(DistributorComissions::class, 'order_id');
    }
    public function getCoupon()
    {
        return $this->belongsTo(cupones::class, 'coupon_id');
    }
    public function getOrderState()
    {
        return $this->belongsTo(ParameterValue::class, 'order_state');
    }
    public function getPaymentType()
    {
        return $this->belongsTo(ParameterValue::class, 'payment_type_vp');
    }
    public function getOrderPaymentState()
    {
        return $this->belongsTo(ParameterValue::class, 'payment_state');
    }
}
