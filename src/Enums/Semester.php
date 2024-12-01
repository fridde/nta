<?php

namespace App\Enums;

enum Semester: int
{
    case VT = 1;
    case HT = 2;

    public function compare(self $semester): int
    {
        return $this->value <=> $semester->value;
    }
}
