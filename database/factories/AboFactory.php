<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Contacts\Contact;
use App\Receipts\Abos\Abo;
use App\Receipts\Invoice;
use Faker\Generator as Faker;

$factory->define(Abo::class, function (Faker $faker) {
    $company = factory(Company::class)->create();
    $contact = factory(Contact::class)->create([
        'company_id' => $company->id,
    ]);
    return [
        'settings_type' => Invoice::class,
        'number' => Abo::nextNumber(now()),
        'company_id' => $company->id,
        'contact_id' => $contact->id,
    ];
});
