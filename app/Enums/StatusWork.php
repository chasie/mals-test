<?php

namespace App\Enums;

enum StatusWork: int
{
    case FINISH_WORK    = 0;
    case WORKING        = 1;
    case BREAK          = 2;
    case DINNER         = 3;
}
