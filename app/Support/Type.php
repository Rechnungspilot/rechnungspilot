<?php

namespace App\Support;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\User;
use App\Userfile;
use Illuminate\Support\Arr;

class Type
{
    const ALL = [
        Contact::TYPE => Contact::class,
        Expense::TYPE => Expense::class,
        Invoice::TYPE => Invoice::class,
        Item::TYPE => Item::class,
        User::TYPE => User::class,
        Userfile::TYPE => Userfile::class,
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