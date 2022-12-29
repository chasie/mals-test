<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    /**
     * Отображение страницы авторизации admin
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('admin.auth.login', ['title' => 'Login']);
    }

    /**
     * Авторизация юзера пост запрос, делает проверку admin
     */
    public function postLogin()
    {
        $login = request()->get('email');
        $password = request()->get('password');

        if (Auth::attempt(['email' => $login, 'password' => $password])) {

            // Проверить активирован или нет
            if (Auth::user()->activation == 1) {
                return response()->json(['success' => 'true'], 200);
            } else {
                Auth::logout();

                return response()->json(['success' => 'false', 'error' => 'Доступ к CRM ограничен.'], 200);
            }
        } elseif (Auth::attempt(['login' => $login, 'password' => $password])) {
            if (Auth::user()->activation == 1) {
                return response()->json(['success' => 'true'], 200);
            } else {
                Auth::logout();

                return response()->json(['success' => 'false', 'error' => 'Доступ к CRM ограничен.'], 200);
            }
        } else {
            return response()->json(['success' => 'false', 'error' => 'Не верный логин или пароль!'], 200);
        }
    }

    /**
     * выход admin
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(URL::to('/admin/login'));
    }
}
