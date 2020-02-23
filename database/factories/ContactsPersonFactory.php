<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contacts\Contact;
use App\Contacts\Person;
use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Person::class, function (Faker $faker, array $attributes = []) {
    $contact = Arr::has($attributes, 'contact_id') ? Contact::find($attributes['contact_id']) : (factory(Contact::class)->create());
    $gender = Arr::random(['male', 'female']);

    return [
        'company_id' => $contact->company_id,
        'contact_id' => $contact->id,
        'title' => $faker->title($gender),
        'firstname' => $faker->firstName($gender),
        'lastname' => $faker->lastName,
        'phonenumber' => $faker->phoneNumber,
        'mobilenumber' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'function' => $faker->jobTitle,
    ];
});
