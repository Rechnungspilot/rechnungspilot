<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Tag;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Tag::class, function (Faker $faker) {
    $name = $faker->word;
    return [
        'company_id' => factory(Company::class),
        'name' => $name,
        'slug' => Str::slug('name'),
        'type' => $faker->word,
    ];
});
