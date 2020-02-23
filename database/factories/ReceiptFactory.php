<?php

use Faker\Generator as Faker;

$factory->define(App\Receipts\Receipt::class, function (Faker $faker) {
    return [
        'number' => App\Receipts\Receipt::nextNumber(),
        'company_id' => function () {
            return factory('App\Company')->create()->id;
        },
        'contact_id' => function () {
            return factory('App\Contacts\Contact')->create()->id;
        },
    ];
});
