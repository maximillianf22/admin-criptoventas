<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GSlider extends Model
{
    protected $fillable = [
        'id',
        'name',
        'url',
        'state'
    ];
    protected $table = 'gsliders';
}
