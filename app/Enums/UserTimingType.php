<?php

namespace App\Enums;

enum UserTimingType: int
{
    case FINISH_WORKDAY     = 0;
    case START_WORKDAY      = 1;
    case BREAK              = 2;
    case DINNER             = 3;
    case DELIVERY           = 4;
    case DUTIES             = 5;
    case MANAGER_TASK       = 6;
    case PAUSE_COLLECTING   = 7;
    case PAUSE_CHECK        = 8;
    case PAUSE_HELP         = 9;

    public function pauseList(): array
    {
        return [
            self::PAUSE_CHECK->value,
            self::PAUSE_COLLECTING->value,
            self::PAUSE_HELP->value,
        ];
    }
}
