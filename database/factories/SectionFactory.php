<?php

use Faker\Generator as Faker;

$factory->define(App\Projects\Section::class, function (Faker $faker) {
    $project = factory('App\Projects\Project')->create();
    return [
        'company_id' => $project->company_id,
        'project_id' => $project->id,
        'name' => $faker->word,
    ];
});
