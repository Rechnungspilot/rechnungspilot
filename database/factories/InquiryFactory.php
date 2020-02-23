<?php

use App\Company;
use App\Contacts\Contact;
use App\Receipts\Inquiries\Inquiry;
use Faker\Generator as Faker;

$factory->define(Inquiry::class, function (Faker $faker) {
    $company = factory(Company::class)->create();
    $contact = factory(Contact::class)->create([
        'company_id' => $company->id,
    ]);
    return [
        'number' => Inquiry::nextNumber(now()),
        'company_id' => $company->id,
        'contact_id' => $contact->id,
    ];
});
