<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSoul extends Model
{
    protected $table="soul_users";

    protected $fillable = [
        'name', 'email', 'phone'
    ];    
   
}
