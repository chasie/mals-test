<?php

namespace App\Http\Controllers;

use App\Duty;
use App\Order;
use App\Orderstatus;

use App\User;
use App\Usertiming;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class WorkerController extends Controller
{
    private function buildTree(array &$elements, $parentId = 0){
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
//                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }


    /*
 * $num число, от которого будет зависеть форма слова
 * $form_for_1 первая форма слова, например Товар
 * $form_for_2 вторая форма слова - Товара
 * $form_for_5 третья форма множественного числа слова - Товаров
 */
    public static function true_wordform($num, $form_for_1, $form_for_2, $form_for_5)
    {
        $num = abs($num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
        $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
        if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
            return $form_for_5;
        if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
            return $form_for_2;
        if ($num_x == 1) // иначе если оканчивается на 1
            return $form_for_1;
        return $form_for_5;
    }

    private function chekIfUserBusy($user)
    {
        $boolen = true;
        if ($user->order_id != null
            || $user->order_id > 0
            || $user->isdelivery == 1
            ||$user->duty_id!= null
            || $user->ismanagertask == 1) {
            $boolen = false;
        }
        return $boolen;
    }

    public function start()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        $user = Auth::user();
        return view('start', ['title' => 'Продуктивного дня, ' . $user->name . '!', 'user' => $user]);
    }

    public function break()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        $user = Auth::user();
        return view('break', ['title' => $user->name, 'user' => $user]);
    }

    public function action()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        $user = Auth::user();
        return view('action', ['title' => $user->name,
            'user' => $user]);
    }

    public function main()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        $user = Auth::user();
        if ($user->status_work != 1) {
            return redirect()->route('start');
        } else {
            if ($user->order_id != null || $user->isdelivery != 0 || $user->duty_id != null || $user->ismanagertask != 0) {
                return redirect()->route('action');
            } else {
                return view('main', ['title' => 'Продуктивного дня, ' . $user->name . '!', 'user' => $user]);
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Заказы
     */
    public function getOrders()
    {
//        if (!Auth::user()->isWorker()) {
//            return response()->json(array('success' => "false", "error" => 'access only worker group'), 200);
//        }
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $user = Auth::user();
        return view('orders.index', ['title' => 'Заказы']);
    }

    public function getOrdersJson()
    {
        $draw = request()->get('draw');
        $start = request()->get("start");
        $rowperpage = request()->get("length"); // Rows display per page

        //ordering
        $order_col = 'id';
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



        $orders = Order::where('id', '>', 0);
        $recordsFiltered = Order::where('id', '>', 0);
        $recordsTotal = Order::where('id', '>', 0);

        if (request()->has('search') && count(request('search'))){
            $search = request('search')['value'];
            if (!empty(trim($search))) {
                $orders = $orders->where(function ($q) use ($search) {
                    $q->where('number', 'LIKE', '%' . $search . '%');
                });
                $recordsFiltered = $recordsFiltered->where(function ($q) use ($search) {
                    $q->where('number', 'LIKE', '%' . $search . '%');
                });
            }
        }

        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();


        $orders = $orders
            ->with('created_user')
            ->with('isgetheruser')
            ->with('ischeckuser')
            ->with('ishelpusers')
            ->withCount(['compleatedstatuses','checkedstatuses'])
            ->orderBy(Orderstatus::select('status')
                ->whereColumn('order_id', 'orders.id')
                ->latest('status')
                ->limit(1)
            )
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        var_dump($orders->toArray());exit;

        $data = [];
        foreach ($orders as $o) {
            $statuses = [];
            if ($o->isgetheruser != null) {
                $statuses['0'] = 'Сборка';
            }
            if ($o->ischeckuser != null) {
                $statuses['1'] = 'Проверка';
            }
            if (count($o->ishelpusers)) {
                $statuses['2'] = 'Помощь';
            }
            //комбинированные статусы
            $isOrderCompleted = $o->compleatedstatuses_count;
            $isOrderChecked = $o->checkedstatuses_count;
            $row_class='';
            if ($isOrderCompleted > 0 && $isOrderChecked >0){
                $row_class= 'bg-success-light';
            }
            if ($isOrderCompleted > 0 && $isOrderChecked == 0){
                $row_class= 'bg-yellow-light';
            }
            $temp_arr = [
                'id' => (string)$o->id,
                'number' => [
                    'id'=>$o->id,
                    'number'=>$o->number,
                    ],
                'price' => ($o->price != null) ? $o->price : ' 0 ',
                'created_user' => ($o->created_user != null) ? $o->created_user->name : ' - ',
                'status_current' => $statuses,
                'actions' => $o->id,
                'status'=>($o->orderstatus != null) ? $o->orderstatus->status : '',
                'compleatedstatuses'=>$isOrderCompleted,
                'checkedstatuses'=>$isOrderChecked,
                'row_class'=>$row_class,
            ];
            $data[] = $temp_arr;
        }


        return response()->json(['data' => $data,
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered
        ], 200);
    }

    public function postOrderModalInfo()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $id = request('order_id');
        $order = Order::find($id);
        if ($order == null) {
            return response()->json(['success' => "false", "error" => 'not found'], 200);
        }
        return view('orders.modal_order_info', ['title' => 'Информация про заказ',
            'order' => $order
            ]);
    }

    public function getOrderAdd()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $duties = Duty::orderBy('name')->get();
        return view('orders.modal', ['title' => 'Добавить заказ', 'duties' => $duties]);
    }

    public function getOrderEdit()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $id = request('id');
        $order = Order::find($id);
        if ($order == null) {
            return response()->json(['success' => "false", "error" => 'not found'], 200);
        }

        return view('orders.modal', ['title' => 'Редактирование заказ',
            'order' => $order,
        ]);
    }

    public function postOrderStore()
    {
        if (request()->has('id') && request('id') != '') {
            $order = Order::find(request('id'));
            if ($order == null) {
                return response()->json(['success' => "false", "error" => 'not found'], 200);
            }
        } else {
            $order = new Order();
            $order->created_user_id = Auth::id();
        }


        $validate_arr = [
            'number',
            'price',
        ];

        foreach ($validate_arr as $item) {
            if (!request()->has($item) || request($item) == '') {
                return response()->json(['success' => "false", 'error' => 'Заполните необходимые поля'], 200);
            }
        }

        if (request()->has('id') && request('id') != '') {
            if (Order::where('number', request('number'))->where('id', '!=', request('id'))->count() > 0) {
                return response()->json(['success' => "false", "error" => 'Номер заказ существует. Введите другой номер'], 200);
            }
        } else {
            if (Order::where('number', request('number'))->count() > 0) {
                return response()->json(['success' => "false", "error" => 'Номер заказ существует. Введите другой номер'], 200);
            }
        }
        $order->number = request('number');
        $order->price = request('price');

        $order->save();

        return response()->json(['success' => "true"], 200);
    }

    public function getOrder($id)
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $order = Order::find($id);
        if ($order == null) {
            return response()->json(['success' => "false", "error" => 'not found'], 200);
        }

        return view('orders.order', ['title' => 'Заказ №' . $order->number,
            'order' => $order,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * начало /окончание работы
     */
    public function workstart()
    {
        $user = Auth::user();
        $user->status_work = 1;
        $user->save();

        $time = new Usertiming();
        $time->user_id = $user->id;
        $time->type = 1;
        $time->start = Carbon::now();
        $time->diff = 0;
        $time->save();

        return response()->json(['success' => "true"], 200);
    }

    public function changestatus()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $user = Auth::user();


        if (request('startorfinish') == 1) {
            //start
            $user->status_work = request('type');//2 перерыв 3 обед //тут тип из фиксации совпадает со статусом в таблице юзера
            $user->save();

            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type = request('type');
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();
        } else {
            //finish
            $user->status_work = 1;//1работает
            $user->save();
            $time = Usertiming::where('user_id', $user->id)
                ->where('type', request('type'))
                ->whereNull('finish')
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
        }

        return response()->json(['success' => "true"], 200);
    }

    public function changestatusWorkPause()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $user = Auth::user();

        if (request('status') == 1) {
            //start
            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type = request('type'); //40,41,42,43
            $time->order_id = request('order_id');
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();
        } else {
            //finish
            $time = Usertiming::where('user_id', $user->id)
                ->where('type', request('type'))
                ->whereNull('finish')
                ->where('order_id', request('order_id'))
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
        }

        return response()->json(['success' => "true"], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * изменение статуса заказа
     */
    public function changestatusorder()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        //status 1 start 0 notworking/finish
        //type 0-сбор 1-проверка 2-помощь в сборке 3-помощь в проверке
        $user = Auth::user();
        $status_last = Orderstatus::where('order_id', request('order_id'))->orderByDesc('created_at')->first();

        if (request('status') == 1) {
            //start
            $boolen = $this->chekIfUserBusy($user);
            if (!$boolen) {
                return response()->json(['success' => "false", 'error' => 'У Вас уже есть заказ в работе!'], 200);
            }

$status_order = new Orderstatus();
            $status_order->order_id = request('order_id');
//            если тип 0 - сборка
            if (request('type') == 0) {
                //если старт то проверка был ли он собран 2ды
                if (Orderstatus::where('order_id', request('order_id'))->where('status', 1)->count() >= 1) {
                    return response()->json(['success' => "false", 'error' => 'Заказ уже был собран'], 200);
                }
                //            //если сейчас в сборке
                if ($status_last != null && $status_last->status == 0) {
                    return response()->json(['success' => "false", 'error' => 'Заказ сейчас в сборке'], 200);
                }
                if ($status_last != null && $status_last->status == 2) {
                    return response()->json(['success' => "false", 'error' => 'Заказ сейчас в проверке'], 200);
                }
                $status_order->status = 0;//сборка
                $status_order->user_id = $user->id;
                $status_order->save();
            }
            //            если тип 1 - проверка
            if (request('type') == 1) {
                //проверка был ли он собран в принципе
                if (Orderstatus::where('order_id', request('order_id'))->where('status', 1)->count() == 0) {
                    return response()->json(['success' => "false", 'error' => 'Заказ еще не собран'], 200);
                }

                //если сейчас в сборке
                if ($status_last != null && $status_last->status == 0) {
                    return response()->json(['success' => "false", 'error' => 'Заказ сейчас в сборке'], 200);
                }
//                if ($status_last != null && $status_last->status == 2) {
//                    return response()->json(array('success' => "false", 'error' => 'Заказ сейчас в проверке'), 200);
//                }
                //проверка по колву собранного 1 сбор 1 проверка 2 сбора может быть 2 проверки максимум
//                $cnt_compleated = Orderstatus::where('order_id', request('order_id'))->where('status', 1)->count();
//                $cnt_checked = Orderstatus::where('order_id', request('order_id'))->where('status', 2)->count();
//                if ($cnt_checked == $cnt_compleated){
//                    return response()->json(array('success' => "false", 'error' => 'Заказ уже проверен'), 200);
//                }

                $status_order->status = 2;//проверка
                $status_order->user_id = $user->id;
                $status_order->save();
            }
            //            если тип 2 - помощь
            if (request('type') == 2) {
                //если сейчас в сборке
                if ($status_last == null || $status_last->status != 0) {
                    return response()->json(['success' => "false", 'error' => 'Заказ никем не собирается'], 200);
                }
//                if ($status_last != null && $status_last->helper_user_id != null) {
//                    return response()->json(array('success' => "false", 'error' => 'Заказ уже помогают собирать'), 200);
//                }
                $status_last->helper_user_id = $user->id;
                $status_last->save();
            }

            if (request('type') == 3) {
                //если сейчас в сборке
                if ($status_last == null || $status_last->status != 2) {
                    return response()->json(['success' => "false", 'error' => 'Заказ никем не проверяется'], 200);
                }
//                if ($status_last != null && $status_last->helper_user_id != null) {
//                    return response()->json(array('success' => "false", 'error' => 'Заказ уже помогают собирать'), 200);
//                }
                $status_last->helper_user_id = $user->id;
                $status_last->save();
            }

            $user->order_id = request('order_id');
            $user->type_order = request('type');
            $user->status_order = request('status');
            $user->save();

            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type_order = request('type');
            $time->order_id = request('order_id');
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();


        } else {
            //finish
            $status_order = new Orderstatus();
            $status_order->order_id = request('order_id');
//            если тип 0 - сборка
            if (request('type') == 0) {
                //если finish то проверка есть ли такой заказ у помощников
                //если да то помощнику автоматически закрываем помощь
                $helper_user = User::where('order_id', request('order_id'))->where('type_order', 2)->first();
                if ($helper_user != null) {
                    $helper_time = Usertiming::where('user_id', $helper_user->id)
                        ->where('type_order', 2)//помощь
                        ->where('order_id', $helper_user->order_id)
                        ->whereNull('finish')
                        ->orderByDesc('created_at')
                        ->first();
                    if ($helper_time != null) {
                        $helper_time->finish = Carbon::now();
                        $helper_time->diff = Carbon::now()->diffInSeconds(Carbon::parse($helper_time->start));
                        $helper_time->save();
                    }
                    $helper_user->order_id = null;
                    $helper_user->type_order = null;
                    $helper_user->status_order = request('status');
                    $helper_user->save();
                }

                $status_order->status = 1;//собрано
                $status_order->user_id = $user->id;
                $status_order->save();
            }
            //            если тип 1 - проверка
            if (request('type') == 1) {
                $status_order->status = 3;//проверено
                $status_order->user_id = $user->id;
                $status_order->save();
            }
            //            если тип 2 - помощь то помощнтк ничего не делает по статусам
            if (request('type') == 2) {
            }


            $time = Usertiming::where('user_id', $user->id)
                ->where('type_order', request('type'))
                ->where('order_id', $user->order_id)
                ->whereNull('finish')
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
            $user->order_id = null;
            $user->type_order = null;
            $user->status_order = request('status');
            $user->save();
        }

        return response()->json(['success' => "true"], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * изменение статуса доставки
     */
    public function changestatusdelivery()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        //status 1 start 0 notworking/finish
        $user = Auth::user();

        if (request('status') == 1) {
            //start
            $boolen = $this->chekIfUserBusy($user);
            if (!$boolen) {
                return response()->json(['success' => "false", 'error' => 'У Вас уже есть работа!'], 200);
            }
            $user->isdelivery = request('status');
            $user->save();

            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type = 10;//доставка
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();


        } else {
            //finish

            $time = Usertiming::where('user_id', $user->id)
                ->where('type', 10)
                ->whereNull('finish')
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
            $user->isdelivery = request('status');
            $user->save();
        }
        return response()->json(['success' => "true"], 200);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * изменение статуса задание руководителя
     */
    public function changestatusmanagertask()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        //status 1 start 0 notworking/finish
        $user = Auth::user();

        if (request('status') == 1) {
            //start
            $boolen = $this->chekIfUserBusy($user);
            if (!$boolen) {
                return response()->json(['success' => "false", 'error' => 'У Вас уже есть работа!'], 200);
            }
            $user->ismanagertask = request('status');
            $user->save();

            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type = 30;//ismanagertask
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();


        } else {
            //finish

            $time = Usertiming::where('user_id', $user->id)
                ->where('type', 30)
                ->whereNull('finish')
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
            $user->ismanagertask = request('status');
            $user->save();
        }
        return response()->json(['success' => "true"], 200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * Рабочие обязанности
     */
    public function getDuties()
    {
        if (!Auth::user()->isWorker()) {
            return response()->json(['success' => "false", "error" => 'access only worker group'], 200);
        }
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        $duties = Duty::
        get()
            ->toArray();
        $duties = $this->buildTree($duties);

        return view('duties.index', ['title' => 'Рабочие обязанности',
            'duties'=>$duties]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * изменение статуса рабочей обязанности
     */
    public function changestatusduty()
    {
        if (Auth::user()->isWorker() && !Auth::user()->isWorking()) {
            Auth::logout();
            return redirect(URL::to('/login'));
        }
        //status 1 start 0 notworking/finish
        $user = Auth::user();

        if (request('status') == 1) {
            //start
            if ($user->order_id != null || $user->order_id > 0 || $user->isdelivery == 1 ||$user->duty_id!= null) {
                return response()->json(['success' => "false", 'error' => 'У Вас уже есть работа!'], 200);
            }
            $user->duty_id = request('duty_id');
            $user->save();

            $time = new Usertiming();
            $time->user_id = $user->id;
            $time->type = 20;//duty
            $time->duty_id = request('duty_id');
            $time->start = Carbon::now();
            $time->diff = 0;
            $time->save();


        } else {
            //finish

            $time = Usertiming::where('user_id', $user->id)
                ->where('type', 20)
                ->whereNull('finish')
                ->orderByDesc('created_at')
                ->first();
            if ($time != null) {
                $time->finish = Carbon::now();
                $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
                $time->save();
            }
            $user->duty_id = null;
            $user->save();
        }
        return response()->json(['success' => "true"], 200);
    }

    public function init_today_statistic_table()
    {
        $user = Auth::user();
        $array = [
            'created' => 0,
            'created_price' => 0,
            'checked' => 0,
            'checked_price' => 0,
            'break' => 0
        ];
        $times = Usertiming::where('start', '>=', Carbon::now()->startOfDay())
            ->where('finish', '<=', Carbon::now()->endOfDay())
            ->where('user_id', Auth::id())
            ->with('order')
            ->get();
        foreach ($times as $row) {
            if ($row->order != null && $row->type_order == 0) {
                $checkByOrderCount = Usertiming::where('start', '>=', Carbon::now()->startOfDay())
                    ->where('finish', '<=', Carbon::now()->endOfDay())
                    ->where('order_id', '=', $row->order->id)->get()->unique('user_id')->count();
                if ($checkByOrderCount < 1) {
                    $checkByOrderCount = 1;
                }
                $array['created']++;
                $array['created_price'] += (int)$row->order->price / $checkByOrderCount;
            }
            if ($row->order != null && $row->type_order == 2) {
                $checkByOrderCount = Usertiming::where('start', '>=', Carbon::now()->startOfDay())
                    ->where('finish', '<=', Carbon::now()->endOfDay())
                    ->where('order_id', '=', $row->order->id)->get()->unique('user_id')->count();
                if ($checkByOrderCount < 1) {
                    $checkByOrderCount = 1;
                }
                $array['created']++;
                $array['created_price'] += (int)$row->order->price / $checkByOrderCount;
            }
            if ($row->order != null && $row->type_order == 1) {
                $array['checked']++;
                $array['checked_price'] += (int)$row->order->price;
            }
            //2021.12.11 Пул тасков от 2021.12.07 добаляю паузы при сборке/проверке/помощи заказов
            if ($row->type == 2 || $row->type == 3 || $row->type == 40 || $row->type == 41 || $row->type == 42 || $row->type == 43) {
                $array['break'] += $row->diff;
            }
        }
        $array['break'] = round((int)$array['break'] / 60, 2);
        return view('orders.init_today_statistic_table', ['array' => $array]);
    }

    public function postOrderDelete()
    {
        if (!Auth::user()->isSAdmin()) {
            return response()->json(['success' => "false", "error" => 'access only admin group'], 200);
        }
        $order = Order::find(request('id'));
        if ($order == null){
            return response()->json(['success' => "false", "error" => 'not found'], 200);
        }
        if  (User::where('order_id', $order->id)->count()>0){
            return response()->json(['success' => "false", "error" => 'Нельзя удалить. Взят в работу'], 200);
        }
        if  (Usertiming::where('order_id', $order->id)->count()>0){
            return response()->json(['success' => "false", "error" => 'Нельзя удалить. Взят в работу'], 200);
        }
        $order->delete();

        return response()->json(['success' => "true"], 200);
    }
}
