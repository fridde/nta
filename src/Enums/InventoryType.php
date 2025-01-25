<?php

namespace App\Enums;

enum InventoryType: int
{
    case BOX = 0;
    case EXTRA_MATERIAL = 1;
    case STOCKROOM = 2;
    case USER_GUIDE = 3;  // Lärarhandledning
    case EQUIPMENT = 4;
}
