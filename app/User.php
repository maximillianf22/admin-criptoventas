<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
        'document',
        'document_type_vp',
        'name',
        'password',
        'last_name',
        'email',
        'cellphone',
        'rol_id',
        'code',
        'user_state',
        'state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getDocType()
    {
        return $this->belongsTo(ParameterValue::class, 'document_type_vp');
    }

    public function getRol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function getAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id')->where('state', 1);
    }

    public function getCommerce()
    {
        return $this->hasOne(Commerce::class, 'user_id');
    }

    public function getCommerceAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id')->where('state', 1);
    }
}
