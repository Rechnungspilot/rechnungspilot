<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Contacts\InteractionType;
use Faker\Generator as Faker;

$factory->define(InteractionType::class, function (Faker $faker) {
    return [
        'company_id' => factory(Company::class),
        'name' => $faker->word,
    ];
});
