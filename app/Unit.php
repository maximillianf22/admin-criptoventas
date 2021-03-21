<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    public function getCommerce()
    {
        return $this->belongsTo(Commerce::class, 'commerce_id');
    }
}
