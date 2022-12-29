<?php

namespace App\Http\Controllers;

use App\Orderstatus;
use App\Usertiming;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function сloseendofday()
    {
//        Log::info('сloseendofday');
        $usertimings = Usertiming::where('created_at', '<', Carbon::now()->startOfDay())
            ->whereNull('finish')
            ->with('user')
            ->orderBy('created_at')
            ->get();
        //order type
        //0 сбор заказа
        //1 проверка
        //2 помощь
        //order status
        //0 сбор заказа
        //1 собран
        //2 проверка
        //3 проверен
        $orderstatuses = [];
        if (count($usertimings)) {
            foreach ($usertimings as $timing) {
                $timing->finish = Carbon::now()->startOfDay()->subSecond();
                $timing->diff = $timing->finish->diffInSeconds(Carbon::parse($timing->start));
                $timing->save();
                //сборка
                if ($timing->type_order === 0) {
                    $orderstatuses[] =
                        ['order_id' => $timing->order_id,
                            'status' => 1,
                            'user_id' => $timing->user_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                }
                //проверка
                if ($timing->type_order === 1) {
                    $orderstatuses[] =
                        ['order_id' => $timing->order_id,
                            'status' => 3,
                            'user_id' => $timing->user_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                }

                $timing->user->status_work = 0;
                $timing->user->order_id = 0;
                $timing->user->type_order = null;
                $timing->user->status_order = 0;
                $timing->user->isdelivery = 0;
                $timing->user->duty_id = null;
                $timing->user->ismanagertask = 0;
                $timing->user->save();
            }
        }
        if (count($orderstatuses)) {
            Orderstatus::insert($orderstatuses);
        }

        return 0;
    }
}
