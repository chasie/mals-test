<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface AuthContract
{
    public function login(
        string  $login,
        string  $password,
        bool    $isAdmin = false
    ): void;

    public function logout(bool $isAdmin = false): void;
}
