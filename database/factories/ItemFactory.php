<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    $unit = factory(App\Unit::class)->create();

    return [
        'company_id' => $unit->company_id,
        'cost_center' => 0,
        'description' => '',
        'name' => $faker->word,
        'number' => '',
        'revenue' => 0,
        'revenue_account_number' => 0,
        'tax' => 0.19,
        'type' => 0,
        'unit_cost' => number_format(0, 6),
        'unit_id' => $unit->id,
        'unit_price' => number_format(0, 6),
    ];
});
