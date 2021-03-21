<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    public function getValues()
    {
        return $this->hasMany(PriceList::class, 'products_id', 'id');
    }
    public function get_Values()
    {
        return $this->belongsTo(PriceList::class, 'products_id', 'id');
    }
}
