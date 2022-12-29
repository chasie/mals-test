<?php

namespace App\Services;

use App\Contracts\Services\AuthContract;
use App\Enums\StatusWork;
use App\Models\User;
use App\Models\UserTiming;
use Carbon\Carbon;

class AuthService implements AuthContract
{

    /**
     * @throws \Exception
     */
    public function login(
        string  $login,
        string  $password,
        bool    $isAdmin = false
    ): void
    {
        $user = User::query();
        if ($isAdmin) {
            $user->where('email', $login);
        } else {
            $user->where('login', $login);
        }

        $user = $user->where('activation', true)->first();

        if (empty($user)) {
            throw new \Exception('Доступ к CRM ограничен.');
        }

        if(!\auth()->attempt(
            ($isAdmin)
            ? [
                'login' => $login,
                'password' => $password,
            ]
            : [
                'email' => $login,
                'password' => $password,
            ])) {
            throw new \Exception(
                ($isAdmin)
                    ? 'Не верный пароль'
                    : 'Не верный пин-код!'
            );
        }
    }

    public function logout(bool $isAdmin = false): void
    {
        if (!$isAdmin) {
            auth()->user()->update([
                'status_work' => StatusWork::FINISH_WORK
            ]);
            $this->abortTiming();
        }
        auth()->logout();
    }

    private function abortTiming(): void
    {
        $time = UserTiming::query()
            ->where('user_id', auth()->user()->id)
            ->where('type',1)
            ->whereNull('finish')
            ->orderBy('created_at','desc')
            ->first();

        if (!empty($time)){
            $time->finish = Carbon::now();
            $time->diff = Carbon::now()->diffInSeconds(Carbon::parse($time->start));
            $time->save();
        }
    }
}
