<?php

use App\Company;
use App\Receipts\Quote;
use Faker\Generator as Faker;

$factory->define(Quote::class, function (Faker $faker) {
    $company = factory(Company::class)->create();
    $contact = factory('App\Contacts\Contact')->create([
        'company_id' => $company->id,
    ]);
    return [
        'number' => Quote::nextNumber(now()),
        'company_id' => $company->id,
        'contact_id' => $contact->id,
    ];
});
