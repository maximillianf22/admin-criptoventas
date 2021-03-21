<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributors';

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
