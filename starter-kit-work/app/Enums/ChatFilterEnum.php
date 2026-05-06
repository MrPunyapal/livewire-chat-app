<?php

declare(strict_types=1);

namespace App\Enums;

enum ChatFilterEnum: string
{
    case Favorites = 'favorites';

    // other upcoming filters (eg: files)
}
