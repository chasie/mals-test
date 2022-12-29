<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderstatus extends Model
{
    protected $table = 'order_statuses';
    //order status
//0 сбор заказа
//1 собран
//2 проверка
//3 проверен
}
