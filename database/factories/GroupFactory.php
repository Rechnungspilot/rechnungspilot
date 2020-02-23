<?php

use Faker\Generator as Faker;

$factory->define(App\Projects\Group::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory('App\Company')->create()->id;
        },
        'name' => $faker->word,
    ];
});
