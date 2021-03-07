<?php

namespace App\Support;

use App\Item;
use Illuminate\Support\Arr;

class Type
{
    const ALL = [
        Item::TYPE => Item::class,
    ];

    public static function class(string $type) : string
    {
        return Arr::get(self::ALL, $type);
    }

    public static function type(string $class) : string
    {
        return array_search($class, self::ALL);
    }
}