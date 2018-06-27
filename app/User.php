<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Role;
use Result;

class User extends Authenticatable
{
    use Notifiable;

    public static $userStatuses = [        
        'Выключен',
        'Активен',
        'Заблокирован'
    ];

    public static function getUserStatus()
    {
        return self::$userStatuses;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'status_id', 
        'is_subscribe',
        'course_id',
        'data_start_course',
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
        return $this->belongsToMany('App\Role');
    }

    public function results() {
        return $this->hasMany('App\Result');
    }   

    public function hasRole($id_role)
    {
        return null !== $this->roles()->where('role_id', $id_role)->first();
    }

     public function updateRole($role_id)
    {
        \DB::table('role_user')->where('user_id', $this->id)->update(['role_id' => $role_id]);
        return $this;
    }


}
