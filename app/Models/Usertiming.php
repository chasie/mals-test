<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usertiming extends Model
{
    protected $table = "user_timings";

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

//type
//1 start finish workday
//2 перерыв
//3 обед

//10 доставка
//20 обязанности
//30 ismanagertask

// type
//40 пауза сбор заказа
//41 пауза проверка
//42 пауза помощь
}
