<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayuLog extends Model
{
    protected $table = 'payu_log';
    protected $fillable = ['transaction_id','message', ];
}
