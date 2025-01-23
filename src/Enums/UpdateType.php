<?php

namespace App\Enums;

enum  UpdateType: string
{
    case BOX_IN_STORAGE_UNCHECKED = 'lager_oviss';
    case BOX_IN_STORAGE_UNFINISHED = 'lager_halvfärdig';
    case BOX_IN_STORAGE_READY = 'lager_klar';
    case BOX_AT_SCHOOL = 'skolan';
}
