<?php

use App\Company;
use App\Item;
use App\Todos\Todo;
use App\Unit;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Time::class, function (Faker $faker) {

    $company = factory(Company::class)->create();
    $creator = factory(User::class)->create([
        'company_id' => $company->id,
    ]);
    $unit = factory(Unit::class)->create([
        'company_id' => $company->id,
    ]);
    $item = factory(Item::class)->create([
        'company_id' => $company->id,
        'unit_id' => $unit->id,
        'type' => Item::TYPE_SERVICE,
    ]);

    return [
        'company_id' => $company->id,
        'item_id' => $item->id,
        'user_id' => $creator->id,
        'start_at' => now(),
        'end_at' => null,
    ];
});
