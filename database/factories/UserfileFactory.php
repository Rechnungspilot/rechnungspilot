<?php

use App\User;
use App\Userfile;
use Faker\Generator as Faker;

$factory->define(Userfile::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $fileExtension = $faker->fileExtension;
    return [
        'company_id' => $user->company_id,
        'user_id' => $user->id,
        'name' => $faker->word,
        'extension' => $fileExtension,
        'original_name' => $faker->uuid . $fileExtension,
        'filename' => $faker->uuid . $fileExtension,
        'mime' => $faker->mimeType,
        'size' => rand(1000, 10000),
        'thumbnail' => false,
        'preview' => false,
    ];
});
