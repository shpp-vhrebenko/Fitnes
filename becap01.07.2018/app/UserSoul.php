<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSoul extends Model
{
    protected $table="soul_users";

    protected $fillable = [
        'name', 'email', 'phone', 'status_id', 'course_id'
    ];

    public static $soulUserStatuses = [
        'Не оплачено',
        'Оплачено'        
    ];    

    public function getSoulUserStatus($status_id)
    {
        return self::$soulUserStatuses[$status_id];
    }    
   
}
