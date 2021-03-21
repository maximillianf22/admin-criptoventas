<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';
    protected $fillable = ['id', 'commerce_id', 'name', 'url', 'state'];
    public function getCommerce()
    {
        return $this->belongsTo('App\Commerce');
    }
}
