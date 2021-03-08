<?php

namespace App\Support;

use App\Contacts\Contact;
use App\Item;
use App\User;
use Illuminate\Support\Arr;

class Type
{
    const ALL = [
        Contact::TYPE => Contact::class,
        Item::TYPE => Item::class,
        User::TYPE => User::class,
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