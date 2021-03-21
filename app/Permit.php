<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    protected $table = 'permits';

    public function getModule()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
