<?php

use Faker\Generator as Faker;

$factory->define(App\Contacts\Contact::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory('App\Company')->create()->id;
        },
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'company' => $faker->company,
        'email' => $faker->email,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastname,
        'number' => $faker->numberBetween(10000, 99999),
        'postcode' => $faker->postcode,
    ];
});
