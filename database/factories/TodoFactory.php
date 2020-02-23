<?php

use Faker\Generator as Faker;

$factory->define(App\Todos\Todo::class, function (Faker $faker) {
    $creator = factory(App\User::class)->create();

    return [
        'name' => '',
        'company_id' => $creator->company_id,
        'creator_id' => $creator->id,
        'start_at' => now(),
    ];
});
