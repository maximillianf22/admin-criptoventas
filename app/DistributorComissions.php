<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistributorComissions extends Model
{
    protected $table = "distributor_comissions";
    protected $fillable = ['order_id', 'distributor_id', 'distributor_code', 'distributor_percent', 'state'];
    public function getDistributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
    public function getOrder()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
