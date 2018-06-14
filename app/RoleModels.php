<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleModels extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function users() {
    	return $this->belongsToMany('App/User', 'role_user', 'role_id', 'user_id');
    }
}
