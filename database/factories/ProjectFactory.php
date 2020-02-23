<?php

use Faker\Generator as Faker;

$factory->define(App\Projects\Project::class, function (Faker $faker) {

    $user = factory(App\User::class)->create();
    $project = factory(App\Projects\Group::class)->create(['company_id' => $user->company_id]);

    return [
        'company_id' => $user->company_id,
        'project_group_id' => $project->id,
        'creator_id' => $user->id,
        'name' => $faker->word,
    ];
});
