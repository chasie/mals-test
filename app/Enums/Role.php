<?php

namespace App\Enums;

enum Role: int
{
    case SYS_ADMIN      = 1;
    case WORKER         = 2;
    case ADMIN          = 3;
    case MANAGER        = 4;

    public function description(): string
    {
        return match ($this) {
            self::SYS_ADMIN     => 'Системный администратор',
            self::WORKER        => 'Сотрудник',
            self::ADMIN         => 'Администратор',
            self::MANAGER       => 'Менеджер',
        };
    }

    public static function getAdminList(): array
    {
        return [
            self::SYS_ADMIN,
            self::ADMIN,
            self::MANAGER,
        ];
    }

    public static function getWorkerGroup(): array
    {
        return [
            self::WORKER
        ];
    }
}
