<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileMin extends Model
{
    protected $fillable = [
        'commerce_id',
        'profile_vp',
        'value',
        'state'
    ];

    protected $table = 'profile_mins';
}
