<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'user_id',
        'profile_vp',
        'distributor_id',
        'distributor_code',
        'distributor_percent',
        'state'
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDistributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
