<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contacts\Contact;
use App\Contacts\Interaction;
use App\Contacts\InteractionType;
use App\Contacts\Person;
use App\User;
use Faker\Generator as Faker;

$factory->define(Interaction::class, function (Faker $faker) {

    $user = factory(User::class)->create();
    $contact = factory(Contact::class)->create([
        'company_id' => $user->company_id,
    ]);
    $person = factory(Person::class)->create([
        'company_id' => $contact->company_id,
        'contact_id' => $contact->id,
    ]);
    $interactionType = factory(InteractionType::class)->create([
        'company_id' => $contact->company_id,
    ]);

    return [
        'company_id' => $contact->company_id,
        'contact_id' => $contact->id,
        'interaction_type_id' => $interactionType->id,
        'person_id' => $person->id,
        'user_id' => $user->id,
        'at' => now(),
        'text' => $faker->paragraph,
    ];
});
