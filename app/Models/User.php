<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function usertimings()
    {
        return $this->hasMany(Usertiming::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function duty()
    {
        return $this->belongsTo(Duty::class);
    }

    public function getName()
    {
        $text = '';
        if ($this->name != null) {
            $text = $this->name;
        }

        return $text;
    }

    public function getGroup()
    {
        if ($this->group_id == 1) {
            return 'Системный администратор';
        } elseif ($this->group_id == 3) {
            return 'Администратор';
        } elseif ($this->group_id == 4) {
            return 'Менеджер';
        } else {
            return 'Сотрудник';
        }
    }

    public function isSAdmin()
    {
        if (Auth::check()) {
            if ($this->group_id == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isAdmin()
    {
        if (Auth::check()) {
            if ($this->group_id == 3) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isWorker()
    {
        if (Auth::check()) {
            if ($this->group_id == 2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isManager()
    {
        if (Auth::check()) {
            if ($this->group_id == 4) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getEmail()
    {
        $email = '';
        if ($this->email != null) {
            $email = $this->email;
        }

        return $email;
    }

    public function isWorking()
    {
        if ($this->status_work == 0) {
            return false;
        }

        return true;
    }

    //заказ на паузе
    public function isOrderPaused()
    {
        if (Usertiming::where('user_id', $this->id)
            ->whereIn('type', [40, 41, 42])//пауза по заказу
            ->whereNull('finish')
            ->count() > 0) {
            return true;
        }

        return false;
    }

    //   work statuses
//0 не наработе
//1 старт работі
//2 перрів
//3 обед

//statud order
//0 - не вполняет
//1 віполняет

//order type
//0 сбор заказа
//1 проверка
//2 помощь

//isdelivery
//0 нет доставки
//1 доставляет

//ismanagertask
//0 нет
//1 работает над заданием

//duty_id
//если не null То работает над обязанностью
}
