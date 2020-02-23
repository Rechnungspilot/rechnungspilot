<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\Models\CustomFields\CustomFieldValue;
use Faker\Generator as Faker;

$factory->define(CustomFieldValue::class, function (Faker $faker) {
    return [
        'company_id' => factory(Company::class),
        'custom_field_id' => factory(CustomField::class),
        'customfieldable_type' => Contact::class,
        'customfieldable_id' =>factory(Contact::class),
        'value' => $faker->word,
    ];
});
