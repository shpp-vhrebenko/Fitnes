<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static $userStatuses = [
        'Активен',
        'Выключен',
        'Заблокирован'
    ];

    public static function getUserStatus()
    {
        return User::$userStatuses;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status_id', 'is_subscribe'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getClientStatus($status_id)
    {
        return self::$userStatuses[$status_id];
    }


    public function roles() {
        return $this->belongsToMany('App/Role');
    }

}
