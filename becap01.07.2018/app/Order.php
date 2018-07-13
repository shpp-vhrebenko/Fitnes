<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\UserSoul;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'status_id',
        'user_status',
        'total'
    ];

    protected $orderStatuses = [
        'Не оплочено',
        'Оплачено',
        'Ожидает оплаты',
    ];

    protected $userStatuses = [
        'Не зарегистрирован',
        'Зарегистрирован',        
    ];

    public static function getAllOrderStatuses()
    {
        $order = new Order();
        return $order->orderStatuses;
    }

    public function getOrderStatus($id)
    {
        return $this->orderStatuses[$id];
    }

    public function getClientStatus($id)
    {
        return $this->userStatuses[$id];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }   

    public function client_not_register()
    {
        return $this->belongsTo(UserSoul::class, 'user_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    }  
}
