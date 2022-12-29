<?php

namespace App\Http\Controllers;

use App\Company;
use App\Passwordreset;
use App\Portfel;
use App\RefferalLinks;
use App\User;
use App\Partner;
use App\Usertiming;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public static function quickRandom($length = 5)
    {
        $n = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($n, 5)), 0, $length);
    }

    /**
     * Отображение страницы авторизации
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('auth.login', array('title' => 'Login'));
    }

    /**
     * Авторизация юзера пост запрос, делает проверку
     */
    public function postLogin()
    {
        $password = request()->get('password');

        if (Auth::attempt(array('login' => $password,'password' => $password))) {

            // Проверить активирован или нет
            if (Auth::user()->activation == 1) {
                return Response::json(array('success' => "true"), 200);
            } else {
                Auth::logout();
                return Response::json(array('success' => "false", 'error' => 'Доступ к CRM ограничен.'), 200);
            }
        } elseif (Auth::attempt(array('login' => $password,'password' => $password))) {
            if (Auth::user()->activation == 1) {
                return Response::json(array('success' => "true"), 200);
            } else {
                Auth::logout();
                return Response::json(array('success' => "false", 'error' => 'Доступ к CRM ограничен.'), 200);
            }
        } else {
            return Response::json(array('success' => "false", 'error' => 'Не верный пин-код!'), 200);
        }
    }

    /**
     * выход
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        $user = Auth::user();
        $user->status_work = 0;
        $user->save();

        $time = Usertiming::where('user_id', $user->id)
            ->where('type',1)
            ->whereNull('finish')
            ->orderBy('created_at','desc')
            ->first();
        if ($time != null){
            $time->finish = Carbon::now();
            $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
            $time->save();
        }
        Auth::logout();
        return Redirect::to(URL::to('/login'));
    }
}
