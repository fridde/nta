<?php

namespace App\Enums;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ACTIVE_USER = 'ROLE_ACTIVE_USER';
    case ADMIN = 'ROLE_ADMIN';

}
