<?php

use App\Company;
use Faker\Generator as Faker;

$factory->define(App\Receipts\Invoice::class, function (Faker $faker) {
    $company = factory(Company::class)->create();
    $contact = factory('App\Contacts\Contact')->create([
        'company_id' => $company->id,
    ]);
    return [
        'number' => App\Receipts\Invoice::nextNumber(now()),
        'company_id' => $company->id,
        'contact_id' => $contact->id,
    ];
});
