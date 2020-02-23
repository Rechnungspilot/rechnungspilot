<?php

use App\Receipts\Invoice;
use Faker\Generator as Faker;

$factory->define(App\Receipts\Term::class, function (Faker $faker) {

    $days = $faker->numberBetween(0, 100);

    return [
        'company_id' => function () {
            return factory('App\Company')->create()->id;
        },
        'name' => $days . ' Tage',
        'days' => $days,
        'default' => false,
        'type' => Invoice::class,
    ];
});
