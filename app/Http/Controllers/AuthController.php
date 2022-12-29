<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\Users\LoginRequest;
use App\Models\Usertiming;
use App\Partner;
use App\Passwordreset;
use App\Portfel;
use App\RefferalLinks;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    )
    {}

    /**
     * Отображение страницы авторизации
     * @return View
     */
    public function login(): View
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Авторизация юзера пост запрос, делает проверку
     */
    public function attempt(LoginRequest $request): JsonResponse
    {
        try {
            $this->authService->login(
                login: $request->get('password'),
                password: $request->get('password'),
            );
            return Response::json([
                'success'   => 'true',
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'success'   => 'false',
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Выход пользователя из аккаунта
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return Redirect::to(URL::to('/login'));
    }


    public static function quickRandom($length = 5)
    {
        $n = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($n, 5)), 0, $length);
    }
}
