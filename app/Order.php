<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

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
        'Зарегистрирован',
        'Не зарегистрирован',
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

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }   
}
