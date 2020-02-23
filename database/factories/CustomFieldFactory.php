<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\CustomFields\CustomField;
use Faker\Generator as Faker;

$factory->define(CustomField::class, function (Faker $faker) {
    return [
        'company_id' => factory('App\Company'),
        'default' => false,
        'for' => 'kontakte',
        'input_type' => 'text',
        'name' => $faker->word,
    ];
});
