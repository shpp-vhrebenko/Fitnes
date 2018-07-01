<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use User;

class Role extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function users() {
    	return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
    }

    public static function all_roles()
    {
        $roles = [];
        foreach (Role::all() as $role)
        {
            array_push($roles, $role->name);
        }
        return $roles;
    }
}
