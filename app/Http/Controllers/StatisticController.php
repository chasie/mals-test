<?php

namespace App\Http\Controllers;

use App\Duty;
use App\Order;
use App\Orderstatus;
use App\User;
use App\Usertiming;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            return response()->json([
                'success' => 'false',
                'error' => 'access only admin group',
            ]);
        }
        $columns_select = [
            //1-5
            //            'name'=>'Сотрудник',
            'shift' => 'Кол-во отработанных смен',
            'order_complete_cnt' => 'Кол-во собранных заказов',
            'order_complete_cnt_per_day' => 'Среднее кол-во собранных заказов в день',
            'order_complete_sum' => 'Сумма собранных заказов',
            //6-10
            'order_checked_cnt' => 'Кол-во проверенных заказов',
            'order_checked_cnt_per_day' => 'Среднее кол-во проверенных заказов в день',
            'order_checked_sum' => 'Сумма проверенных заказов',
            'order_helped_cnt' => 'Кол-во заказов, кот. помогал собирать',
            'order_helped_cnt_per_day' => 'Среднее кол-во заказов, кот. помогал собирать в день',
            //11-15
            'order_helped_sum' => 'Сумма заказов, кот. помогал собирать',
            'vvp' => 'ВВП на сотрудника',
            'order_complete_time' => 'Время на сборку заказов',
            'order_complete_time_per_order' => 'Время на сборку 1 заказа',
            'order_checked_time' => 'Время на проверку заказов',
            //16-20
            'order_checked_time_per_order' => 'Время на проверку 1 заказа',
            'order_helped_time' => 'Время на помощь в сборке заказов',
            'break_time' => 'Время на перерывы',
            'break_time_per_day' => 'Среднее время перерывов в день',
            'free_time' => 'Время простоя',
            //21-25
            'free_time_per_day' => 'Среднее время простоя в день',
            'delivery_time' => 'Время на доставку',
            'delivery_time_per_day' => 'Среднее время доставки в день',
            'manager_task_time' => 'Время на поручения руководителя',
            'manager_task_time_per_day' => 'Среднее время на поручения руководителя в день',
            //26-27
            'duty_time' => 'Время на рабочие обязанности',
            'duty_time_per_day' => 'Среднее время на рабочие обязанности в день',
        ];
        $duties_req = Duty::orderBy('name')->get();
        foreach ($duties_req as $duty) {
            $columns_select[$duty->id.'_duty'] = 'Время на '.$duty->name;
            $columns_select[$duty->id.'_duty_per_day'] = 'Ср. время на '.$duty->name;
        }

        $users = User::where('activation', 1)->where('group_id', '>', 1)->orderBy('name')->get();

        return view('admin.statistics.index', [
            'title' => 'Статистика',
            'users' => $users,
            'columns_select' => $columns_select,
        ]);
    }

    public function initTable()
    {
//        $statistics = Usertiming::where('id','>',0);
//        $recordsFiltered = Usertiming::where('id','>',0);
//        $recordsTotal = Usertiming::where('id','>',0);

        $date_from = Carbon::now()->subMonth()->startOfDay();
        $date_to = Carbon::now()->endOfDay();
        if (request()->has('filter__date_from') && request('filter__date_from') != '') {
            $date_from = Carbon::createFromFormat('d.m.Y', request('filter__date_from'))->startOfDay();
        }
        if (request()->has('filter__date_to') && request('filter__date_to') != '') {
            $date_to = Carbon::createFromFormat('d.m.Y', request('filter__date_to'))->endOfDay();
        }
        $statistics = Usertiming::where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);
        $recordsFiltered = Usertiming::where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);

        if (request()->has('filter__user') && count(request('filter__user'))) {
            $statistics = $statistics->where(function ($q) {
                $q->whereIn('user_id', request('filter__user'));
            });
            $recordsFiltered = $recordsFiltered->where(function ($q) {
                $q->whereIn('user_id', request('filter__user'));
            });
        }
        $recordsTotal = Usertiming::count();
        $recordsFiltered = $recordsFiltered->count();

//        $statistics = $statistics
//            ->orderBy($order_col,$order_direction);

        $statistics = $statistics
            ->with('user')
            ->with('order');
        $statistics = $statistics
                ->get();

        $data = [];
        $data_users = [];
        $data_totals = [
            //1-5
            'name' => 'Общее',
            'shift' => 0,
            'order_complete_cnt' => 0,
            'order_complete_cnt_per_day' => 0,
            'order_complete_sum' => 0,
            //6-10
            'order_checked_cnt' => 0,
            'order_checked_cnt_per_day' => 0,
            'order_checked_sum' => 0,
            'order_helped_cnt' => 0,
            'order_helped_cnt_per_day' => 0,
            //11-15
            'order_helped_sum' => 0,
            'vvp' => 0,
            'order_complete_time' => 0,
            'order_complete_time_per_order' => 0,
            'order_checked_time' => 0,
            //16-20
            'order_checked_time_per_order' => 0,
            'order_helped_time' => 0,
            'break_time' => 0,
            'break_time_per_day' => 0,
            'free_time' => 0,
            //21-25
            'free_time_per_day' => 0,
            'delivery_time' => 0,
            'delivery_time_per_day' => 0,
            'manager_task_time' => 0,
            'manager_task_time_per_day' => 0,
            //26-27
            'duty_time' => 0,
            'duty_time_per_day' => 0,
        ];
        $columns = [
            [
                'title' => 'Сотрудник',
                'data' => 'name',
            ],
            [
                'title' => 'Кол-во отработанных смен',
                'data' => 'shift',
            ],
            [
                'title' => 'Кол-во собранных заказов',
                'data' => 'order_complete_cnt',
            ],
            [
                'title' => 'Среднее кол-во собранных заказов в день',
                'data' => 'order_complete_cnt_per_day',
            ],
            [
                'title' => 'Сумма собранных заказов',
                'data' => 'order_complete_sum',
            ],
            //6-10
            [
                'title' => 'Кол-во проверенных заказов',
                'data' => 'order_checked_cnt',
            ],
            [
                'title' => 'Среднее кол-во проверенных заказов в день',
                'data' => 'order_checked_cnt_per_day',
            ],
            [
                'title' => 'Сумма проверенных заказов',
                'data' => 'order_checked_sum',
            ],
            [
                'title' => 'Кол-во заказов, кот. помогал собирать',
                'data' => 'order_helped_cnt',
            ],
            [
                'title' => 'Среднее кол-во заказов, кот. помогал собирать в день',
                'data' => 'order_helped_cnt_per_day',
            ],
            //11-15
            [
                'title' => 'Сумма заказов, кот. помогал собирать',
                'data' => 'order_helped_sum',
            ],
            [
                'title' => 'ВВП на сотрудника',
                'data' => 'vvp',
            ],
            [
                'title' => 'Время на сборку заказов',
                'data' => 'order_complete_time',
            ],
            [
                'title' => 'Время на сборку 1 заказа',
                'data' => 'order_complete_time_per_order',
            ],
            [
                'title' => 'Время на проверку заказов',
                'data' => 'order_checked_time',
            ],
            //16-20
            [
                'title' => 'Время на проверку 1 заказа',
                'data' => 'order_checked_time_per_order',
            ],
            [
                'title' => 'Время на помощь в сборке заказов',
                'data' => 'order_helped_time',
            ],
            [
                'title' => 'Время на перерывы',
                'data' => 'break_time',
            ],
            [
                'title' => 'Среднее время перерывов в день',
                'data' => 'break_time_per_day',
            ],
            [
                'title' => 'Время простоя',
                'data' => 'free_time',
            ],
            //21-25
            [
                'title' => 'Среднее время простоя в день',
                'data' => 'free_time_per_day',
            ],
            [
                'title' => 'Время на доставку',
                'data' => 'delivery_time',
            ],
            [
                'title' => 'Среднее время доставки в день',
                'data' => 'delivery_time_per_day',
            ],
            [
                'title' => 'Время на поручения руководителя',
                'data' => 'manager_task_time',
            ],
            [
                'title' => 'Среднее время на поручения руководителя в день',
                'data' => 'manager_task_time_per_day',
            ],
            //26-27
            [
                'title' => 'Время на рабочие обязанности',
                'data' => 'duty_time',
            ],
            [
                'title' => 'Среднее время на рабочие обязанности в день',
                'data' => 'duty_time_per_day',
            ],
        ];
        //массив для рабочих обязанностей
        $duties_req = Duty::orderBy('name')->get();
        $duties_temp = [];
        foreach ($duties_req as $duty) {
            $data_totals[$duty->id.'_duty'] = 0;
            $data_totals[$duty->id.'_duty_per_day'] = 0;
            $duties_temp[$duty->id.'_duty'] = 0;
            $duties_temp[$duty->id.'_duty_per_day'] = 0;

            $columns[] = [
                'title' => 'Время на '.$duty->name,
                'data' => $duty->id.'_duty',
            ];
            $columns[] = [
                'title' => 'Ср. время на '.$duty->name,
                'data' => $duty->id.'_duty_per_day',
            ];
        }

        $work_time = 30600; //510*60сек = 8ч30мин рабочий день с 9,30-18,00
        foreach ($statistics as $statistic) {
            $user_id = $statistic->user_id;
            if (! array_key_exists($user_id, $data_users)) {
                $data_users[$user_id] = [
                    //1-5
                    'name' => ($statistic->user != null) ? $statistic->user->name : ' - ',
                    'shift' => [],
                    'order_complete_cnt' => 0,
                    'order_complete_cnt_per_day' => 0,
                    'order_complete_sum' => 0,
                    //6-10
                    'order_checked_cnt' => 0,
                    'order_checked_cnt_per_day' => 0,
                    'order_checked_sum' => 0,
                    'order_helped_cnt' => 0,
                    'order_helped_cnt_per_day' => 0,
                    //11-15
                    'order_helped_sum' => 0,
                    'vvp' => 0,
                    'order_complete_time' => 0,
                    'order_complete_time_per_order' => 0,
                    'order_checked_time' => 0,
                    //16-20
                    'order_checked_time_per_order' => 0,
                    'order_helped_time' => 0,
                    'break_time' => 0,
                    'break_time_per_day' => 0,
                    'free_time' => 0,
                    //21-25
                    'free_time_per_day' => 0,
                    'delivery_time' => 0,
                    'delivery_time_per_day' => 0,
                    'manager_task_time' => 0,
                    'manager_task_time_per_day' => 0,
                    //26-27
                    'duty_time' => 0,
                    'duty_time_per_day' => 0,
                    //доплнительніе массивы для расчета простоя
                    'date_arrays' => [
                        //                        'date'=>[
                        //                            'work_time'=> 0,
                        //                            'order_time'=> 0,
                        //                            'break_time'=> 0,
                        //доп данные которые считают все занятое время кроме переыва
                        //'busy_time'=> 0,
                        //                        ]
                    ],
                ];
                foreach ($duties_temp as $k => $v) {
                    $data_users[$user_id][$k] = $v;
                }
            }
            $date = Carbon::parse($statistic->start)->format('d.m.Y');
            if (! in_array($date, $data_users[$user_id]['shift'])) {
                $data_users[$user_id]['shift'][] = $date;
            }
            if (! array_key_exists($date, $data_users[$user_id]['date_arrays'])) {
                $data_users[$user_id]['date_arrays'][$date] = [
                    'work_time' => 0,
                    'order_time' => 0,
                    'break_time' => 0,
                    'busy_time' => 0,
                    'order_pause_40' => 0,
                    'order_pause_41' => 0,
                    'order_pause_42' => 0,
                ];
            }

            //type
            //1 start finish workday
            //2 перерыв
            //3 обед
            //10 доставка
            //20 обязанности
            //30 managertask

            ////40 пауза сбор заказа
            ////41 пауза проверка
            ////42 пауза помощь
            $type = $statistic->type;
            if ($type !== null && $statistic->diff !== null) {
                if ($type == 2 || $type == 3) {
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                    $data_users[$user_id]['date_arrays'][$date]['break_time'] += (int) $statistic->diff;
                    $data_totals['break_time'] += (int) $statistic->diff;
                }
                if ($type == 1) {
                    $data_users[$user_id]['date_arrays'][$date]['work_time'] += (int) $statistic->diff;
                }
                if ($type == 40) {
                    $data_users[$user_id]['date_arrays'][$date]['order_pause_40'] += (int) $statistic->diff;
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                    $data_users[$user_id]['date_arrays'][$date]['break_time'] += (int) $statistic->diff;
                    $data_totals['break_time'] += (int) $statistic->diff;
                }
                if ($type == 41) {
                    $data_users[$user_id]['date_arrays'][$date]['order_pause_41'] += (int) $statistic->diff;
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                    $data_users[$user_id]['date_arrays'][$date]['break_time'] += (int) $statistic->diff;
                    $data_totals['break_time'] += (int) $statistic->diff;
                }
                if ($type == 42) {
                    $data_users[$user_id]['date_arrays'][$date]['order_pause_42'] += (int) $statistic->diff;
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                    $data_users[$user_id]['date_arrays'][$date]['break_time'] += (int) $statistic->diff;
                    $data_totals['break_time'] += (int) $statistic->diff;
                }
            }

            //order type
            //0 сбор заказа
            //1 проверка
            //2 помощь
            $type_order = $statistic->type_order;

            if ($type_order !== null && $statistic->diff !== null) {
                $price = 0;
                if ($statistic->order != null) {
                    $price = (float) $statistic->order->price;
                }
                if ($type_order === 0) {
                    $data_users[$user_id]['order_complete_cnt']++;
                    $data_users[$user_id]['order_complete_sum'] += $price;
                    $data_users[$user_id]['order_complete_time'] += (int) $statistic->diff;

                    $data_totals['order_complete_cnt']++;
                    $data_totals['order_complete_sum'] += $price;
                    $data_totals['order_complete_time'] += (int) $statistic->diff;
                }
                if ($type_order == 1) {
                    $data_users[$user_id]['order_checked_cnt']++;
                    $data_users[$user_id]['order_checked_sum'] += $price;
                    $data_users[$user_id]['order_checked_time'] += (int) $statistic->diff;

                    $data_totals['order_checked_cnt']++;
                    $data_totals['order_checked_sum'] += $price;
                    $data_totals['order_checked_time'] += (int) $statistic->diff;
                }
                if ($type_order == 2) {
                    $defaultCountWithHelp = $statistics
                        ->where('order_id', '=', $statistic->order_id)->unique('user_id')->count();
                    if ($defaultCountWithHelp < 1) {
                        $defaultCountWithHelp = 1;
                    }

                    $price = $price / $defaultCountWithHelp;
//                    dd($statistic, );
                    $data_users[$user_id]['order_helped_cnt']++;
                    $data_users[$user_id]['order_helped_sum'] += $price;
                    $data_users[$user_id]['order_helped_time'] += (int) $statistic->diff;

                    $data_totals['order_helped_cnt']++;
                    $data_totals['order_helped_sum'] += $price;
                    $data_totals['order_helped_time'] += (int) $statistic->diff;
                }
                if ($type_order == 3) {
                    $defaultCountWithHelp = $statistics
                        ->where('order_id', '=', $statistic->order_id)->unique('user_id')->count();
                    if ($defaultCountWithHelp < 1) {
                        $defaultCountWithHelp = 1;
                    }

//                    $price = $price / $defaultCountWithHelp;
////                    dd($statistic, );
//                    $data_users[$user_id]['order_helped_cnt']++;
//                    $data_users[$user_id]['order_helped_sum']+=$price;
//                    $data_users[$user_id]['order_helped_time']+=(int)$statistic->diff;
//
//                    $data_totals['order_helped_cnt']++;
//                    $data_totals['order_helped_sum']+=$price;
//                    $data_totals['order_helped_time'] += (int)$statistic->diff;
                }
                $data_users[$user_id]['date_arrays'][$date]['order_time'] += (int) $statistic->diff;
                $data_users[$user_id]['date_arrays'][$date]['busy_time'] += (int) $statistic->diff;
            }

            if ($type !== null && $statistic->diff !== null) {
                //delivery
                if ($type == 10) {
                    $data_users[$user_id]['delivery_time'] += (int) $statistic->diff;

                    $data_totals['delivery_time'] += (int) $statistic->diff;

                    $data_users[$user_id]['date_arrays'][$date]['busy_time'] += (int) $statistic->diff;
                }

                //duties
                if ($type == 20) {
                    if (array_key_exists($statistic->duty_id.'_duty', $data_users[$user_id])) {
                        $data_users[$user_id]['duty_time'] += (int) $statistic->diff;

                        $data_totals['duty_time'] += (int) $statistic->diff;

                        $data_users[$user_id]['date_arrays'][$date]['busy_time'] += (int) $statistic->diff;

                        $data_users[$user_id][$statistic->duty_id.'_duty'] += (int) $statistic->diff;
                        $data_totals[$statistic->duty_id.'_duty'] += (int) $statistic->diff;
                    }
                }

                //managertask
                if ($type == 30) {
                    $data_users[$user_id]['manager_task_time'] += (int) $statistic->diff;

                    $data_totals['manager_task_time'] += (int) $statistic->diff;

                    $data_users[$user_id]['date_arrays'][$date]['busy_time'] += (int) $statistic->diff;
                }
            }
        }
        if (count($data_users)) {
            foreach ($data_users as $u) {
                $temp_arr = $u;
                $temp_arr['shift'] = count($u['shift']);
                $data_totals['shift'] += $temp_arr['shift'];
                if ($temp_arr['shift'] != 0) {
                    $temp_arr['order_complete_cnt_per_day'] = round($u['order_complete_cnt'] / $temp_arr['shift'], 2);
                    $temp_arr['order_checked_cnt_per_day'] = round($u['order_checked_cnt'] / $temp_arr['shift'], 2);
                    $temp_arr['order_helped_cnt_per_day'] = round($u['order_helped_cnt'] / $temp_arr['shift'], 2);
                    //среднее время на доставку в день
                    $temp_arr['delivery_time_per_day'] = self::getTimeFromseconds(round($u['delivery_time'] / $temp_arr['shift']));
                    //среднее время на рабочие обязанности в день
                    $temp_arr['duty_time_per_day'] = self::getTimeFromseconds(round($u['duty_time'] / $temp_arr['shift']));
                    //среднее время на задания руководства
                    $temp_arr['manager_task_time_per_day'] = self::getTimeFromseconds(round($u['manager_task_time'] / $temp_arr['shift']));

                    //на каждую обязанность среднее
                    foreach ($duties_req as $duty) {
                        $temp_arr[$duty->id.'_duty_per_day'] = self::getTimeFromseconds(round($u[$duty->id.'_duty'] / $temp_arr['shift']));
                        $temp_arr[$duty->id.'_duty'] = self::getTimeFromseconds($u[$duty->id.'_duty']);
                    }
                }

                $temp_arr['order_complete_time'] = self::getTimeFromseconds($u['order_complete_time']);
                if ($u['order_complete_cnt'] != 0) {
                    $temp_arr['order_complete_time_per_order'] = self::getTimeFromseconds($u['order_complete_time'] / $u['order_complete_cnt']);
                }
                $temp_arr['order_checked_time'] = self::getTimeFromseconds($u['order_checked_time']);
                if ($u['order_checked_cnt'] != 0) {
                    $temp_arr['order_checked_time_per_order'] = self::getTimeFromseconds($u['order_checked_time'] / $u['order_checked_cnt']);
                }
                $temp_arr['order_helped_time'] = self::getTimeFromseconds($u['order_helped_time']);

                $temp_arr['break_time'] = self::getTimeFromseconds($u['break_time']);
                if ($temp_arr['shift'] != 0) {
                    $temp_arr['break_time_per_day'] = self::getTimeFromseconds($u['break_time'] / $temp_arr['shift']);
                }

                $temp_arr['delivery_time'] = self::getTimeFromseconds($u['delivery_time']);
                $temp_arr['duty_time'] = self::getTimeFromseconds($u['duty_time']);
                $temp_arr['manager_task_time'] = self::getTimeFromseconds($u['manager_task_time']);

                //вычет пока идет не из фактического времени а из нормативного

                if (count($u['date_arrays'])) {
                    foreach ($u['date_arrays'] as $d) {
                        //2021.12.11 Пул тасков от 2021.12.07 вычту из времени занятости пауз сборке/проверки/помощи заказов это време учтено в переывах
                        $temp_busy_time = $d['busy_time'] - $d['order_pause_40'] - $d['order_pause_41'] - $d['order_pause_42'];
                        $temp_arr['free_time'] += $work_time - $temp_busy_time - $d['break_time'];
                        $data_totals['free_time'] += $work_time - $temp_busy_time - $d['break_time'];
                    }
                    $temp_arr['free_time_per_day'] = self::getTimeFromseconds($temp_arr['free_time'] / $temp_arr['shift']);
                    $temp_arr['free_time'] = self::getTimeFromseconds($temp_arr['free_time']);
                    if ($temp_arr['free_time'] < 0) {
                        $temp_arr['free_time'] = 0;
                    }
                    if ($temp_arr['free_time_per_day'] < 0) {
                        $temp_arr['free_time_per_day'] = 0;
                    }
                }

                //подсчет vvp ВВП =  (сумма собранных заказов + сумма проверенных заказов)/2
                $temp_arr['vvp'] = round(($u['order_complete_sum'] + $u['order_checked_sum']) / 2);

                unset($temp_arr['date_arrays']);
                $data[] = $temp_arr;
            }
        }

        if (count($data_totals)) {
            if ($data_totals['shift'] != 0) {
                $data_totals['order_complete_cnt_per_day'] = round($data_totals['order_complete_cnt'] / $data_totals['shift'], 2);
                $data_totals['order_checked_cnt_per_day'] = round($data_totals['order_checked_cnt'] / $data_totals['shift'], 2);
                $data_totals['order_helped_cnt_per_day'] = round($data_totals['order_helped_cnt'] / $data_totals['shift'], 2);

                $data_totals['break_time_per_day'] = self::getTimeFromseconds($data_totals['break_time'] / $data_totals['shift']);

                $data_totals['free_time_per_day'] = self::getTimeFromseconds($data_totals['free_time'] / $data_totals['shift']);
                if ($data_totals['free_time_per_day'] < 0) {
                    $data_totals['free_time_per_day'] = 0;
                }
                //среднее время на доставку в день
                $data_totals['delivery_time_per_day'] = self::getTimeFromseconds(round($data_totals['delivery_time'] / $data_totals['shift']));
                //среднее время на рабочие обязанности в день
                $data_totals['duty_time_per_day'] = self::getTimeFromseconds(round($data_totals['duty_time'] / $data_totals['shift']));
                //среднее время на задания руководства в день
                $data_totals['manager_task_time_per_day'] = self::getTimeFromseconds(round($data_totals['manager_task_time'] / $data_totals['shift']));
                //на каждую обязанность среднее
                foreach ($duties_req as $duty) {
                    $data_totals[$duty->id.'_duty_per_day'] = self::getTimeFromseconds(round($data_totals[$duty->id.'_duty'] / $data_totals['shift']));
                    $data_totals[$duty->id.'_duty'] = self::getTimeFromseconds($data_totals[$duty->id.'_duty']);
                }
            }
            if ($data_totals['order_complete_cnt'] != 0) {
                $data_totals['order_complete_time_per_order'] = self::getTimeFromseconds($data_totals['order_complete_time'] / $data_totals['shift']);
            }
            if ($data_totals['order_checked_cnt'] != 0) {
                $data_totals['order_checked_time_per_order'] = self::getTimeFromseconds($data_totals['order_checked_time'] / $data_totals['order_checked_cnt']);
            }
            //note тут я не учитывал вычет пауз при сборке / проврке / помощи из order time
            $data_totals['order_complete_time'] = self::getTimeFromseconds($data_totals['order_complete_time']);
            $data_totals['order_checked_time'] = self::getTimeFromseconds($data_totals['order_checked_time']);
            $data_totals['order_helped_time'] = self::getTimeFromseconds($data_totals['order_helped_time']);
            $data_totals['break_time'] = self::getTimeFromseconds($data_totals['break_time']);
            $data_totals['free_time'] = self::getTimeFromseconds($data_totals['free_time']);
            if ($data_totals['free_time'] < 0) {
                $data_totals['free_time'] = 0;
            }
            $data_totals['delivery_time'] = self::getTimeFromseconds($data_totals['delivery_time']);
            $data_totals['duty_time'] = self::getTimeFromseconds($data_totals['duty_time']);
            $data_totals['manager_task_time'] = self::getTimeFromseconds($data_totals['manager_task_time']);
            //подсчет vvp ВВП =  (сумма собранных заказов + сумма проверенных заказов)/2
            $data_totals['vvp'] = round(($data_totals['order_complete_sum'] + $data_totals['order_checked_sum']) / 2);
        }
        if (request()->has('filter__cols') && count(request('filter__cols'))) {
            $arr_exlude = request('filter__cols');
            foreach ($data as $keyv => $user_data) {
                foreach ($user_data as $key => $value) {
                    if (in_array($key, $arr_exlude)) {
                        unset($data[$keyv][$key]);
                    }
                }
            }
            foreach ($data_totals as $key => $value) {
                if (in_array($key, $arr_exlude)) {
                    unset($data_totals[$key]);
                }
            }
            foreach ($columns as $key => $column) {
                if (in_array($column['data'], $arr_exlude)) {
                    unset($columns[$key]);
                }
            }
        }

        return view('admin.statistics.init_table', ['title' => 'статистика',
            'data' => $data,
            'data_totals' => $data_totals,
            'columns' => $columns,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ]);
    }

    public static function getTimeFromseconds($seconds)
    {
        return round($seconds / 60, 2);
    }

    public function getDayStatistics()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }
        $users = User::where('activation', 1)->where('group_id', '>', 1)->orderBy('name')->get();

        return view('admin.statistics.day_statistic', ['title' => 'Дневная статистика',
            'users' => $users,
        ]);
    }

    public function getJsonDayStatistics()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $rowperpage = request()->get('length'); // Rows display per page

        //ordering
        $order_col = 'name';
        $order_direction = 'asc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])) {
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)) {
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number]) && isset($cols[$col_number]['data'])) {
                $data = $cols[$col_number]['data'];
                $order_col = $data;
            }
        }

        $statistics = Usertiming::where('id', '>', 0);
        $recordsFiltered = Usertiming::where('id', '>', 0);
        $recordsTotal = Usertiming::where('id', '>', 0);

        $date_from = Carbon::now()->startOfDay();
        $date_to = Carbon::now()->endOfDay();
        if (request()->has('filter__date') && request('filter__date') != '') {
            $date_from = Carbon::createFromFormat('d.m.Y', request('filter__date'))->startOfDay();
            $date_to = Carbon::createFromFormat('d.m.Y', request('filter__date'))->endOfDay();
        }
        $statistics = $statistics->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);
        $recordsFiltered = $recordsFiltered->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);

        if (request()->has('filter__user') && count(request('filter__user'))) {
            $statistics = $statistics->where(function ($q) {
                $q->whereIn('user_id', request('filter__user'));
            });
            $recordsFiltered = $recordsFiltered->where(function ($q) {
                $q->whereIn('user_id', request('filter__user'));
            });
        }
        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();

//        $statistics = $statistics
//            ->orderBy($order_col,$order_direction);

        $statistics = $statistics
            ->with('user')
            ->with('order');

        if ($rowperpage != -1) {
            $statistics = $statistics
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {
            $statistics = $statistics
                ->get();
        }

        $data = [];
        $data_users = [];

        $work_time = 30600; //510*60сек = 8ч30мин рабочий день с 9,30-18,00

        foreach ($statistics as $statistic) {
            $user_id = $statistic->user_id;
            if (! array_key_exists($user_id, $data_users)) {
                $data_users[$user_id] = [
                    //            1-5
                    'name' => [
                        'id' => $statistic->user_id,
                        'name' => ($statistic->user != null) ? $statistic->user->name : ' - ',
                        'date' => $date_from->copy()->format('d.m.Y'),
                    ],
                    'work_start' => 0,
                    'work_finish' => 0,
                    'islate' => 0,
                    'break_time' => 0,
                    //6-9
                    'dinner_start' => 0,
                    'dinner_finish' => 0,
                    'dinner_time' => 0,
                    'islatedinner' => 'Нет',
                    'free_time' => 0,
                    'busy_time' => 0,
                    'work_time' => $work_time,
                    //Пул тасков 2021.12.07 ввп для невной статистики
                    //подсчет vvp ВВП =  (сумма собранных заказов + сумма проверенных заказов)/2
                    'order_complete_sum' => 0,
                    'order_checked_sum' => 0,
                    'vvp' => 0,
                    'order_pause_40' => 0,
                    'order_pause_41' => 0,
                    'order_pause_42' => 0,
                ];
            }

            //type
            //1 start finish workday
            //2 перерыв
            //3 обед
            //10 доставка
            //20 обязанности
            //30 managertask

            //40 пауза сбор заказа
            //41 пауза проверка
            //42 пауза помощь
            $type = $statistic->type;
            if ($type !== null && $statistic->diff !== null) {
                if ($type == 2) {
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                }
                if ($type == 3) {
                    $data_users[$user_id]['dinner_time'] += (int) $statistic->diff;
                    $data_users[$user_id]['break_time'] += (int) $statistic->diff;

                    $data_users[$user_id]['dinner_start'] = Carbon::parse($statistic->start)->format('d.m.Y H:i');
                    $data_users[$user_id]['dinner_finish'] = Carbon::parse($statistic->finish)->format('d.m.Y H:i');
                    $data_users[$user_id]['islatedinner'] = (Carbon::parse($statistic->start)->diffInSeconds(Carbon::parse($statistic->finish)) > 3600) ? 'Да' : 'Нет';
                }
                if ($type == 1) {
                    $data_users[$user_id]['work_start'] = Carbon::parse($statistic->start)->format('d.m.Y H:i');
                    $data_users[$user_id]['work_finish'] = Carbon::parse($statistic->finish)->format('d.m.Y H:i');
                    if ($statistic->finish == null) {
                        $data_users[$user_id]['work_time'] = Carbon::parse($statistic->finish)->diffInSeconds(Carbon::parse($statistic->start));
                    }
                    $islate = 'Нет';
                    if (Carbon::parse($statistic->start) > Carbon::createFromFormat('d.m.Y', request('filter__date'))->startOfDay()->addHours(9)->addMinutes(30)) {
                        $islate = 'Да';
                    }
                    $data_users[$user_id]['islate'] = $islate;
                }

                if ($type == 10 || $type == 20 || $type == 30) {
                    if ($statistic->diff !== null) {
                        $data_users[$user_id]['busy_time'] += (int) $statistic->diff;
                    }
                }

                if ($type == 40 || $type == 41 || $type == 42) {
                    if ($statistic->diff !== null) {
                        $data_users[$user_id]['break_time'] += (int) $statistic->diff;
                    }
                    if ($type == 40) {
                        $data_users[$user_id]['order_pause_40'] += (int) $statistic->diff;
                    }
                    if ($type == 41) {
                        $data_users[$user_id]['order_pause_41'] += (int) $statistic->diff;
                    }
                    if ($type == 42) {
                        $data_users[$user_id]['order_pause_42'] += (int) $statistic->diff;
                    }
                }
            }
            //order type
            //0 сбор заказа
            //1 проверка
            //2 помощь
            //подсчет сколько на заказы потрачено

            $type_order = $statistic->type_order;

            if ($type_order !== null && $statistic->diff !== null) {
                $price = 0;
                if ($statistic->order != null) {
                    $price = (float) $statistic->order->price;
                }
                if ($type_order === 0) {
                    $data_users[$user_id]['order_complete_sum'] += $price;
                }
                if ($type_order == 1) {
                    $data_users[$user_id]['order_checked_sum'] += $price;
                }
                $data_users[$user_id]['busy_time'] += (int) $statistic->diff;
            }
        }
        if (count($data_users)) {
            foreach ($data_users as $u) {
                $temp_arr = $u;
                //вычет пока идет не из фактического времени а из нормативного кроме текущего дня
                $wt = $u['work_time'];
                if ($wt > $work_time) {
                    $wt = $work_time;
                }
                //2021.12.11 Пул тасков от 2021.12.07 вычту из времени занятости паузыв сборке/проверки/помощи заказов так как оно учтено в перерыве
                $temp_busy_time = $u['busy_time'] - $u['order_pause_40'] - $u['order_pause_41'] - $u['order_pause_42'];
                $temp_arr['free_time'] = self::getTimeFromseconds($wt - $temp_busy_time - $u['break_time']);
                if ($temp_arr['free_time'] < 0) {
                    $temp_arr['free_time'] = 0;
                }
                $temp_arr['break_time'] = self::getTimeFromseconds($u['break_time']);
                //Пул тасков 2021.12.07 ввп для невной статистики
                //подсчет vvp ВВП =  (сумма собранных заказов + сумма проверенных заказов)/2
                $temp_arr['vvp'] = round(($u['order_complete_sum'] + $u['order_checked_sum']) / 2);
                $data[] = $temp_arr;
            }
        }

        //Сортируем в Алфавитном порядке по имени
        if ($order_col == 'name') {
            usort($data, function ($a, $b) use ($order_col, $order_direction) {
                if ($order_direction == 'asc') {
                    return strcmp($a[$order_col]['name'], $b[$order_col]['name']);
                } else {
                    return strcmp($b[$order_col]['name'], $a[$order_col]['name']);
                }
            });
        } else {
            usort($data, function ($a, $b) use ($order_col, $order_direction) {
                if ($order_direction == 'asc') {
                    if ($a[$order_col] == $b[$order_col]) {
                        return 0;
                    }

                    return $a[$order_col] > $b[$order_col] ? 1 : -1;
                } else {
                    if ($a[$order_col] == $b[$order_col]) {
                        return 0;
                    }

                    return $a[$order_col] < $b[$order_col] ? 1 : -1;
                }
            });
        }

        return response()->json(['data' => $data,
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ], 200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * orders statistics
     */
    public function getOrderStatistics()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }

        return view('admin.statistics.order_statistics', ['title' => 'Заказы статистика',

        ]);
    }

    public function getJsonOrderStatistics()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $rowperpage = request()->get('length'); // Rows display per page

        //ordering
        $order_col = 'date';
        $order_direction = 'desc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])) {
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)) {
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number]) && isset($cols[$col_number]['data'])) {
                $data = $cols[$col_number]['data'];
                $order_col = $data;
            }
        }

        $statistics = Usertiming::where('id', '>', 0)->whereNotNull('order_id');
        $recordsFiltered = Usertiming::where('id', '>', 0)->whereNotNull('order_id');
        $recordsTotal = Usertiming::where('id', '>', 0)->whereNotNull('order_id');

        $date_from = Carbon::now()->subMonth()->startOfDay();
        $date_to = Carbon::now()->endOfDay();
        if (request()->has('filter__date_from') && request('filter__date_from') != '') {
            $date_from = Carbon::createFromFormat('d.m.Y', request('filter__date_from'))->startOfDay();
        }
        if (request()->has('filter__date_to') && request('filter__date_to') != '') {
            $date_to = Carbon::createFromFormat('d.m.Y', request('filter__date_to'))->endOfDay();
        }
        $statistics = $statistics->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);
        $recordsFiltered = $recordsFiltered->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);

        if (request()->has('filter__order_number') && request('filter__order_number') != '') {
            $search = request('filter__order_number');
            if (! empty(trim($search))) {
                $order_search = Order::where('number', 'LIKE', '%'.$search.'%')->pluck('id');
                $statistics = $statistics->where(function ($q) use ($order_search) {
                    $q->whereIn('order_id', $order_search);
                });
                $recordsFiltered = $recordsFiltered->where(function ($q) use ($order_search) {
                    $q->whereIn('order_id', $order_search);
                });
            }
        }
        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();

//        $statistics = $statistics
//            ->orderBy($order_col,$order_direction);

        $statistics = $statistics
            ->with('user')
            ->with('order');

        if ($rowperpage != -1) {
            $statistics = $statistics
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {
            $statistics = $statistics
                ->get();
        }

        $data = [];
        $data_orders = [];

        foreach ($statistics as $statistic) {
            $temp_arr = [];
            if ($statistic->order != null) {
                $order_id = $statistic->order_id;
                if (! array_key_exists($order_id, $data_orders)) {
                    $data_orders[$order_id] = [];
                }
                $order = $statistic->order;

                $date = Carbon::parse($statistic->start);
                $start_date = $date->copy()->format('d.m.Y');
                $start_time = $date->copy()->format('H:i');
                $finish_time = ($statistic->finish != null) ? Carbon::parse($statistic->finish)->format('H:i') : ' - ';
                $diff = ($statistic->diff !== null) ? self::getTimeFromseconds($statistic->diff) : ' - ';
                $user_name = ($statistic->user != null) ? $statistic->user->name : ' - ';
                $temp_arr = [
                    'actions' => $order->id,
                    'number' => [
                        'id' => $order->id,
                        'number' => $order->number,
                    ],
                    'price' => $order->price,
                    'date_full' => $date,
                    'date' => $start_date,
                    'order_complete_time_start' => '',
                    'order_complete_time_finish' => '',
                    'order_complete_time_diff' => '',
                    'order_complete_name' => '',
                    'order_checked_time_start' => '',
                    'order_checked_time_finish' => '',
                    'order_checked_time_diff' => '',
                    'order_checked_name' => '',
                    'order_helped_time_start' => '',
                    'order_helped_time_finish' => '',
                    'order_helped_time_diff' => '',
                    'order_helped_name' => '',
                    'ischecked' => '',
                ];

                //order type
                //0 сбор заказа
                //1 проверка
                //2 помощь

                $type_order = $statistic->type_order;
                if ($type_order !== null && $statistic->diff !== null) {
                    if ($type_order === 0) {
                        $temp_arr['order_complete_time_start'] = $start_time;
                        $temp_arr['order_complete_time_finish'] = $finish_time;
                        $temp_arr['order_complete_time_diff'] = $diff;
                        $temp_arr['order_complete_name'] = $user_name;
                    }
                    if ($type_order == 1) {
                        $temp_arr['order_checked_time_start'] = $start_time;
                        $temp_arr['order_checked_time_finish'] = $finish_time;
                        $temp_arr['order_checked_time_diff'] = $diff;
                        $temp_arr['order_checked_name'] = $user_name;
                        $temp_arr['ischecked'] = 'Да';
                    }
                    if ($type_order == 2) {
                        $temp_arr['order_helped_time_start'] = $start_time;
                        $temp_arr['order_helped_time_finish'] = $finish_time;
                        $temp_arr['order_helped_time_diff'] = $diff;
                        $temp_arr['order_helped_name'] = $user_name;
                    }
                }
//            $data[]=$temp_arr;
                $data_orders[$order_id][] = $temp_arr;
            }
        }
        if (count($data_orders)) {
            foreach ($data_orders as $order_id => $data_order_arr) {
                $temp_order = [];
                foreach ($data_order_arr as $elements) {
                    foreach ($elements as $name => $value) {
                        if (! array_key_exists($name, $temp_order)) {
                            $temp_order[$name] = '';
                        }
                        if ($value != '') {
                            $temp_order[$name] = $value;
                        }
                    }
                }
                $data[] = $temp_order;
            }
        }
//        var_dump($data);

        //Сортируем в Алфавитном порядке по имени
        if ($order_col == 'order_complete_name' || $order_col == 'order_checked_name' || $order_col == 'order_helped_name') {
            usort($data, function ($a, $b) use ($order_col, $order_direction) {
                if ($order_direction == 'asc') {
                    return strcmp($a[$order_col], $b[$order_col]);
                } else {
                    return strcmp($b[$order_col], $a[$order_col]);
                }
            });
        } elseif ($order_col == 'date') {
            usort($data, function ($a, $b) use ($order_direction) {
                if ($order_direction == 'asc') {
                    if ($a['date_full'] == $b['date_full']) {
                        return 0;
                    }

                    return $a['date_full'] > $b['date_full'] ? 1 : -1;
                } else {
                    if ($a['date_full'] == $b['date_full']) {
                        return 0;
                    }

                    return $a['date_full'] < $b['date_full'] ? 1 : -1;
                }
            });
        } else {
            usort($data, function ($a, $b) use ($order_col, $order_direction) {
                if ($order_direction == 'asc') {
                    if ($a[$order_col] == $b[$order_col]) {
                        return 0;
                    }

                    return $a[$order_col] > $b[$order_col] ? 1 : -1;
                } else {
                    if ($a[$order_col] == $b[$order_col]) {
                        return 0;
                    }

                    return $a[$order_col] < $b[$order_col] ? 1 : -1;
                }
            });
        }

        return response()->json(['data' => $data,
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ], 200);
    }

    public function getRealTimeStatistics()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }

        return view('admin.statistics.realtime_statistics', ['title' => 'Real Time',
        ]);
    }

    public function getJsonRealTimeStatistics()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $rowperpage = request()->get('length'); // Rows display per page

        //ordering
        $order_col = 'name';
        $order_direction = 'asc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])) {
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)) {
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number]) && isset($cols[$col_number]['data'])) {
                $data = $cols[$col_number]['data'];
                $order_col = $data;
            }
        }

        $statistics = Usertiming::where('id', '>', 0);
        $recordsFiltered = Usertiming::where('id', '>', 0);
        $recordsTotal = Usertiming::where('id', '>', 0);

        $date_from = Carbon::now()->startOfDay();
        $date_to = Carbon::now()->endOfDay();
        if (request()->has('filter__date') && request('filter__date') != '') {
            $date_from = Carbon::createFromFormat('d.m.Y', request('filter__date'))->startOfDay();
            $date_to = Carbon::createFromFormat('d.m.Y', request('filter__date'))->endOfDay();
        }
        $statistics = $statistics->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);
        $recordsFiltered = $recordsFiltered->where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to);
        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();

        $statistics = $statistics
            ->with('user')
            ->with('order');

        $statistics = $statistics
                ->get();
        $data = [
            ['Дата: ', Carbon::now()->format('d.m.Y'), '', ''],
            ['Время: ', Carbon::now()->format('H:i'), '', ''],
        ];
        $order_complete_cnt = 0;
        $order_complete_price = 0;
        $order_checked_cnt = 0;
        $order_checked_price = 0;

        foreach ($statistics as $statistic) {
            //order type
            //0 сбор заказа
            //1 проверка
            //2 помощь
            //подсчет сколько на заказы потрачено
            $type_order = $statistic->type_order;
            if ($type_order !== null && $statistic->diff !== null) {
                if ($type_order == 0) {
                    $order_complete_cnt++;
                    $order_complete_price += (float) $statistic->order->price;
                }
                if ($type_order == 1) {
                    $order_checked_cnt++;
                    $order_checked_price += (float) $statistic->order->price;
                }
            }
        }
        $data[] = [
            'Собрано заказов: ', $order_complete_cnt, 'На сумму: ', $order_complete_price,
        ];
        $data[] = [
            'Проверено заказов: ', $order_checked_cnt, 'На сумму: ', $order_checked_price,
        ];

        return response()->json(['data' => $data,
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ], 200);
    }

    public function getJsonRealTimeStatistics2()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $rowperpage = request()->get('length'); // Rows display per page

        //ordering
        $order_col = 'name';
        $order_direction = 'asc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])) {
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)) {
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number]) && isset($cols[$col_number]['data'])) {
                $data = $cols[$col_number]['data'];
                $order_col = $data;
            }
        }

        $users = User::where('group_id', '>', 1)->where('activation', 1);
        $recordsFiltered = User::where('group_id', '>', 0)->where('activation', 1);
        $recordsTotal = User::where('group_id', '>', 0)->where('activation', 1);

        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();

        $users = $users
            ->orderBy('name')
                ->get();
        $data = [];

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
        //1 выполняет

        //duty_id
        //если не null То работает над обязанностью
        $statuses_curent = [
            '0' => [
                'id' => '0',
                'text' => 'Не работает',
            ],
            '1' => [
                'id' => '1',
                'text' => 'Простой',
            ],
            '2' => [
                'id' => '2',
                'text' => 'Перерыв',
            ],
            '3' => [
                'id' => '3',
                'text' => 'Обед',
            ],
            '4' => [
                'id' => '4',
                'text' => 'Сбор заказа',
            ],
            '5' => [
                'id' => '5',
                'text' => 'Проверка заказа',
            ],
            '6' => [
                'id' => '6',
                'text' => 'Помощь в сборке',
            ],
            '7' => [
                'id' => '7',
                'text' => 'Доставка',
            ],
            '8' => [
                'id' => '8',
                'text' => 'Рабочая обязанность',
            ],
            '9' => [
                'id' => '9',
                'text' => 'Поручение руководителя',
            ],
        ];

        foreach ($users as $user) {
            $temp_arr = [
                'name' => $user->name,
                'status' => [],
            ];
            if ($user->status_work == 0) {
                $temp_arr['status'] = $statuses_curent[0];
            }
            if ($user->status_work == 1) {
                $temp_arr['status'] = $statuses_curent[1];
                if ($user->order_id != null) {
                    if ($user->type_order == 0) {
                        $temp_arr['status'] = $statuses_curent[4];
                    }
                    if ($user->type_order == 1) {
                        $temp_arr['status'] = $statuses_curent[5];
                    }
                    if ($user->type_order == 2) {
                        $temp_arr['status'] = $statuses_curent[6];
                    }
                }
                if ($user->isdelivery == 1) {
                    $temp_arr['status'] = $statuses_curent[7];
                }
                if ($user->duty_id != null) {
                    $temp_arr['status'] = $statuses_curent[8];
                }
                if ($user->ismanagertask == 1) {
                    $temp_arr['status'] = $statuses_curent[9];
                }
            }

            if ($user->status_work == 2) {
                $temp_arr['status'] = $statuses_curent[2];
            }
            if ($user->status_work == 3) {
                $temp_arr['status'] = $statuses_curent[3];
            }
            $data[] = $temp_arr;
        }

        return response()->json(['data' => $data,
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ], 200);
    }

    /**
     * Принудительное удаление проверко и сборок заказов
     */
    public function postDeleteCheckOrder()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }
        $order = Order::find(request('order_id'));
        if ($order == null) {
            return response()->json(['success' => 'false', 'error' => 'not found'], 200);
        }
        //order type
        //0 сбор заказа
        //1 проверка
        //2 помощь
        $statistics = Usertiming::where('order_id', $order->id)
            ->where('type_order', 1)
            ->first();
        if ($statistics == null) {
            return response()->json(['success' => 'false', 'error' => 'Проверка еще не выполнена'], 200);
        }
        if ($statistics->finish == null) {
            return response()->json(['success' => 'false', 'error' => 'Нужно завершить проверку, что бы удалить'], 200);
        }
        $statistics->delete();
        //2021.12.11 Пул тасков от 2021.12.07 удяляю паузы проверки
        Usertiming::where('order_id', $order->id)->where('type', 41)->delete();
        //order status
        //0 сбор заказа
        //1 собран
        //2 проверка
        //3 проверен
        Orderstatus::where('order_id', $order->id)->whereIn('status', [2, 3])->delete();

        return response()->json(['success' => 'true'], 200);
    }

    public function postDeleteCompleteOrder()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }
        $order = Order::find(request('order_id'));
        if ($order == null) {
            return response()->json(['success' => 'false', 'error' => 'not found'], 200);
        }
        //order type
        //0 сбор заказа
        //1 проверка
        //2 помощь
        $statistics = Usertiming::where('order_id', $order->id)
            ->where('type_order', 0)
            ->first();
        if ($statistics == null) {
            return response()->json(['success' => 'false', 'error' => 'Сборка еще не выполнена'], 200);
        }
        if ($statistics->finish == null) {
            return response()->json(['success' => 'false', 'error' => 'Нужно завершить сборку, что бы удалить'], 200);
        }
        if (Usertiming::where('order_id', $order->id)->whereNull('finish')->count()) {
            return response()->json(['success' => 'false', 'error' => 'Нужно завершить работу по заказу, что бы удалить'], 200);
        }

        //order status
        //0 сбор заказа
        //1 собран
        //2 проверка
        //3 проверен
        Usertiming::where('order_id', $order->id)->delete();
        Orderstatus::where('order_id', $order->id)->delete();

        return response()->json(['success' => 'true'], 200);
    }

    public function postModalStatisticsUserInfo()
    {
        if (! Auth::user()->isSAdmin() && ! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            return response()->json(['success' => 'false', 'error' => 'access only admin group'], 200);
        }
        $date = Carbon::createFromFormat('d.m.Y', request('date'));
        $usertimings = Usertiming::where('user_id', request('user_id'))->where('created_at', '>=', $date->copy()->startOfDay())
                    ->where('created_at', '<=', $date->copy()->endOfDay())
                    ->orderBy('start')
                    ->get();
        $user = User::find(request('user_id'));
        $timing_arr = [];
        $start_of_day = null;
        $end_of_day = null;
        foreach ($usertimings as $timing) {
            if ($timing->type == 1) {
                $start_of_day['time'] = Carbon::parse($timing->start)->format('H:i');
                $start_of_day['text'] = 'Приступил к работе';
                $timing_arr[] = $start_of_day;
                if ($timing->finish != null) {
                    $end_of_day['time'] = Carbon::parse($timing->finish)->format('H:i');
                    $end_of_day['text'] = 'Закончил смену';
                }
            } else {
                $t_f = '';
                $t_s = Carbon::parse($timing->start)->format('H:i');
                if ($timing->finish != null) {
                    $t_f = Carbon::parse($timing->finish)->format('H:i');
                }
                if ($timing->type == 2) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Перерыв',
                    ];
                }
                if ($timing->type == 3) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Обед',
                    ];
                }
                if ($timing->type_order == 0) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Сборка заказа',
                    ];
                }
                if ($timing->type_order == 1) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Проверка заказа',
                    ];
                }
                if ($timing->type_order == 2) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Помощь в сборке заказа',
                    ];
                }
                if ($timing->type == 10) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Доставка',
                    ];
                }
                if ($timing->type == 20) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Рабочие обязанности',
                    ];
                }
                if ($timing->type == 30) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Задания руководителя',
                    ];
                }
                if ($timing->type == 40 || $timing->type == 41 || $timing->type == 42) {
                    $timing_arr[] = [
                        'time' => $t_s.' - '.$t_f,
                        'text' => 'Пауза в работе',
                    ];
                }
            }
        }
        if ($end_of_day != null) {
            $timing_arr[] = $end_of_day;
        }

        return view('admin.statistics.modal_user_info', ['title' => 'User Info',
            'user' => $user,
            'timing_arr' => $timing_arr,

        ]);
    }
}
