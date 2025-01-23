<?php

namespace App\Enums;

use Carbon\Carbon;

enum Semester: int
{
    case VT = 1;
    case HT = 2;

    public function compare(self $semester): int
    {
        return $this->value <=> $semester->value;
    }

    public static function getSemesterForDate(\DateTime $date): self
    {
        return Carbon::instance($date)->month <= 6 ? self::VT : self::HT;
    }
}
