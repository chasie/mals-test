<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public function duties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ordeduty::class,'order_id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class,'created_user_id','id');
    }


    public function isgetheruser()
    {
        return $this->hasOne(User::class,'order_id','id')->where('type_order',0);
    }

    public function ischeckuser()
    {
        return $this->hasOne(User::class,'order_id','id')->where('type_order',1);
    }
    public function ishelpusers()
    {
        return $this->hasMany(User::class,'order_id','id')->where('type_order',2);
    }
    public function ishelpuser()
    {
        return $this->hasOne(User::class,'order_id','id')->where('type_order',2);
    }
    public function orderstatus()
    {
        return $this->hasOne(Orderstatus::class)->orderBy('created_at','desc');
    }

    public function compleatedstatuses()
    {
        return $this->hasMany(Orderstatus::class,'order_id','id')->where('status',1);
    }

    public function checkedstatuses()
    {
        return $this->hasMany(Orderstatus::class,'order_id','id')->where('status',3);
    }

    //order type
//0 сбор заказа
//1 проверка
//2 помощь
    public function getherstatistic()
    {
        return $this->hasOne(Usertiming::class, 'order_id', 'id')
            ->where('type_order', '0')
            ->orderBy('created_at', 'desc');
    }
        public function checkstatistic()
    {
        return $this->hasOne(Usertiming::class, 'order_id', 'id')
            ->where('type_order', '1')
            ->orderBy('created_at','desc');
    }
    public function helpstatistic()
    {
        return $this->hasOne(Usertiming::class, 'order_id', 'id')
            ->where('type_order', '2')
            ->orderBy('created_at','desc');
    }

}
