<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Contacts\Contact;
use App\Receipts\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $company = factory(Company::class)->create();
    $contact = factory(Contact::class)->create([
        'company_id' => $company->id,
    ]);
    return [
        'number' => Order::nextNumber(now()),
        'company_id' => $company->id,
        'contact_id' => $contact->id,
    ];
});
