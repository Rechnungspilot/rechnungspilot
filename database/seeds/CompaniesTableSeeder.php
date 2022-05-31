<?php

use App\Templates\Standard;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = factory('App\Company')->create([
            'address' => 'Forststraße 31',
            'bankname' => 'Consorsbank',
            'bic' => '',
            'city' => 'Minden',
            'firstname' => 'Daniel',
            'iban' => '',
            'lastname' => 'Sundermeier',
            'name' => 'Rechnungspilot',
            'phonenumber' => '0123 456789',
            'postcode' => 32423,
            'web' => 'www.rechnungspilot.de',
        ]);

        $company->setup();
    }
}
