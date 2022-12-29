<?php

namespace App\Http\Controllers;

use App\Finance;
use App\History;
use App\Portfel;
use App\Portfelfixstockamount;
use App\Portfeltemp;
use App\Scoring;
use App\Stock;
use App\Tarif;
use App\Ticket;
use App\Tickettext;
use App\User;
use App\Userstatus;
use App\Usertiming;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index()
    {
        if (!Auth::user()->isSAdmin() && !Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return Response::json(['success' => "false", "error" => 'access only admin group'], 200);
        }

        return view('admin.users.index', ['title' => 'Пользователи',
            ]);
    }

    public function getJson()
    {
        $draw = request()->get('draw');
        $start = request()->get("start");
        $rowperpage = request()->get("length"); // Rows display per page

        //ordering
        $order_col = 'first_name';
        $order_direction = 'asc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])){
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)){
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number])  && isset($cols[$col_number]['data'])){
                $data = $cols[$col_number]['data'];
                $order_col = $data;
            }
        }


        $users = User::where('activation', 1);
        $recordsFiltered = User::where('activation', 1);
        $recordsTotal = User::where('activation', 1);

        if (request()->has('filter__search') && request('filter__search') != ''){
            $search = request('filter__search');
            if (!empty(trim($search))) {
                $users = $users->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
                $recordsFiltered = $recordsFiltered->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            }
        }
        $recordsTotal = $recordsTotal->count();
        $recordsFiltered = $recordsFiltered->count();

            $users = $users
                ->orderBy($order_col,$order_direction);

        $users = $users
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data=[];
        foreach ($users as $u){
            $temp_arr = [
                'id'=>(string)$u->id,
                'group_id'=>$u->getGroup(),
                'name'=>$u->name,
                'birthday'=>($u->birthday != null)?(Carbon::parse($u->birthday)->format('d.m.Y')):' - ',
                'actions'=>[
                    'id'=>$u->id,
                    'group_id'=>$u->group_id
                ],
            ];
            $data[] = $temp_arr;
        }



        return Response::json(['data' =>  $data,
            "draw" => intval($draw),
            "recordsTotal"=>$recordsTotal,
            "recordsFiltered"=>$recordsFiltered,
        ], 200);
    }


    public function edit()
    {
        $id =request('id');
        $user = User::find($id);
        if ($user == null) {
            return Response::json(['success' => "false", "error" => 'not found'], 200);
        }
        return view('admin.users.modal', ['title' => 'Редактирование пользователя',
            'user' => $user,
        ]);
    }
    public function editpin()
    {
        $id =request('id');
        $user = User::find($id);
        if ($user == null) {
            return Response::json(['success' => "false", "error" => 'not found'], 200);
        }
        return view('admin.users.modalpin', ['title' => 'Редактирование пин-кода',
            'user' => $user,
        ]);
    }
    public function editpass()
    {
        $id =request('id');
        $user = User::find($id);
        if ($user == null) {
            return Response::json(['success' => "false", "error" => 'not found'], 200);
        }
        return view('admin.users.modalpass', ['title' => 'Редактирование пароля',
            'user' => $user,
        ]);
    }

    public function add()
    {
        return view('admin.users.modal', ['title' => 'Добавить пользователя']);
    }

    public function store()
    {
        if (request()->has('id') && request('id') != ''){
            $user = User::find(request('id'));
            if ($user == null) {
                return Response::json(['success' => "false", "error" => 'not found'], 200);
            }
        } else {
            $user = new User();
        }

        $validate_arr = [
            'name',
            'group_id',
        ];
        if (!request()->has('id') || request('id') == ''){
            $validate_arr[] = 'password';
        }

        foreach ($validate_arr as $item) {
            if (!request()->has($item) || request($item) == '') {
                return Response::json(['success' => "false", 'error' => 'Заполните необходимые поля'], 200);
            }
        }
        if (request('group_id') == 1 ) {
            if (!request()->has('email') || request('email') == '') {
                return Response::json(['success' => "false", 'error' => 'Заполните необходимые поля'], 200);
            }
        }

        $user->name = request('name');
        $user->group_id = request('group_id');
        $user->activation = 1;
        $user->birthday = null;
        if(request()->has('birthday') && request('birthday') != null){
            $user->birthday = Carbon::createFromFormat('d.m.Y', request('birthday'))->startOfDay();
        }
        if (request('group_id') == 2){
            if (request()->has('password') && request('password') != ''){
                $all_users = User::where('group_id',2)->get();
                if  (count($all_users)){
                    foreach ($all_users as $u){
                        if (Hash::check(request('password'), $u->password)) {
                            return Response::json(['success' => "false", 'error' => 'Такой пин-код уже используется'], 200);
                        }
                    }
                }
                $user->password = Hash::make(request()->get('password'));
                $user->remember_token = Hash::make($user->password);
                $user->login = request()->get('password');
            }
        }
        //admin
        if (request('group_id') == 1 || request('group_id') == 3 || request('group_id') == 4 ){
            if (request()->has('password') && request('password') != ''){
                $user->password = Hash::make(request()->get('password'));
                $user->remember_token = Hash::make($user->password);
            }
                if (request()->has('id')){
                    if (User::where('group_id', '!=',2)->where('id', '!=', request('id'))->where('email',request('email'))->count()>0){
                        return Response::json(['success' => "false", 'error' => 'Такой email уже используется'], 200);
                    }
                } else {
                    if (User::where('group_id', '!=',2)->where('email',request('email'))->count()>0){
                        return Response::json(['success' => "false", 'error' => 'Такой email уже используется'], 200);
                    }
                }
                $user->email = request()->get('email');
        }

        $user->save();

        return Response::json(['success' => "true"], 200);
    }

    public function changepin()
    {
            $user = User::find(request('id'));
            if ($user == null) {
                return Response::json(['success' => "false", "error" => 'not found'], 200);
            }


        $validate_arr = [
            'password',
        ];

        foreach ($validate_arr as $item) {
            if (!request()->has($item) || request($item) == '') {
                return Response::json(['success' => "false", 'error' => 'Заполните необходимые поля'], 200);
            }
        }

            $all_users = User::all();
            if  (count($all_users)){
                foreach ($all_users as $u){
                    if (Hash::check(request('password'), $u->password)) {
                        return Response::json(['success' => "false", 'error' => 'Такой пин-код уже используется'], 200);
                    }
                }
            }
            $user->password = Hash::make(request()->get('password'));
            $user->remember_token = Hash::make($user->password);
            $user->login = request()->get('password');
        $user->save();

        return Response::json(['success' => "true"], 200);
    }


    public function changepass()
    {
            $user = User::find(request('id'));
            if ($user == null) {
                return Response::json(['success' => "false", "error" => 'not found'], 200);
            }

        $validate_arr = [
            'password',
        ];

        foreach ($validate_arr as $item) {
            if (!request()->has($item) || request($item) == '') {
                return Response::json(['success' => "false", 'error' => 'Заполните необходимые поля'], 200);
            }
        }
            $user->password = Hash::make(request()->get('password'));
            $user->remember_token = Hash::make($user->password);
        $user->save();

        return Response::json(['success' => "true"], 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return Response::json(['success' => "false", "error" => 'not found'], 200);
        }
        $user->delete();

        return Response::json(['success' => "true"], 200);
    }

    public function shiftclose()
    {
        if (!Auth::user()->isSAdmin()) {
            return Response::json(['success' => "false", "error" => 'access only admin group'], 200);
        }
        $user_id = request('user_id');
        if  (Usertiming::where('user_id',$user_id)->whereNotIn('type',[1,2,3])->whereNull('finish')->count() > 0){
//            Если не работа не отдых и есть открытая статистика то работает
            return Response::json(['success' => "false", "error" => 'Сотрудник работает'], 200);
        }
        if  (Usertiming::where('user_id',$user_id)->whereNotNull('type_order')->whereNull('finish')->count() > 0){
//            Если не работа не отдых и есть открытая статистика то работает
            return Response::json(['success' => "false", "error" => 'Сотрудник работает над заказом'], 200);
        }
        $user_timings = Usertiming::where('user_id', $user_id)->whereNull('finish')->get();
        foreach ($user_timings as  $timing){
            if  ($timing->type == 1 || $timing->type == 2 || $timing->type == 3){
                $timing->finish = Carbon::now();
                $timing->diff = Carbon::now()->diffInSeconds(Carbon::parse($timing->start));
                $timing->save();
            }
        }
        $user=User::find($user_id);
        $user->status_work = 0;
        $user->save();

        return Response::json(['success' => "true"], 200);
    }
}
