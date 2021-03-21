<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tips extends Model
{
    protected $table = 'tips';

    public function getCommerce()
    {
        return $this->belongsTo(Commerce::class, 'commerce_id');
    }
}
