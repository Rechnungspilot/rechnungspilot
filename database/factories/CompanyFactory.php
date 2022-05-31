<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'accountholdername' => $faker->firstname . ' ' . $faker->lastname,
        'address' => $faker->streetAddress,
        'bankname' => $faker->company,
        'bic' => $faker->md5,
        'city' => $faker->city,
        'districtcourt' => $faker->city,
        'email' => $faker->email,
        'euvatnumber' => $faker->md5,
        'faxnumber' => $faker->phonenumber,
        'firstname' => $faker->firstName,
        'iban' => $faker->md5,
        'lastname' => $faker->lastname,
        'phonenumber' => $faker->phonenumber,
        'postcode' => $faker->postcode,
        'traderegister' => $faker->md5,
        'vatnumber' => $faker->md5,
        'invoice_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'order_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'quote_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'delivery_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'expense_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'abo_name_format' => '#DATUM-JJ#-#NUMMER-4#',
        'web' => $faker->domainName,
    ];
});
