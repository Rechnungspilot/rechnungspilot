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

        // $company = factory('App\Company')->create([
        //     'accountholdername' => 'Laura Stock'
        //     'address' => 'Bavenhauser Str. 16',
        //     'bankname' => 'Volksbank Bad Salzuﬂen',
        //     'bic' => 'GENODEM1BSU',
        //     'city' => 'Kalletal',
        //     'email' => 'moin@kuestenkinddesign.de',
        //     'euvatnumber' => 'DE312225648',
        //     'firstname' => 'Laura',
        //     'iban' => 'DE49 4829 1490 4402 1102 00',
        //     'lastname' => 'Stock',
        //     'name' => 'Laura Stock - Küstenkinddesign',
        //     'phonenumber' => '0160 970 67 545',
        //     'postcode' => 32689,
        //     'web' => 'www.kuestenkinddesign.de',
        // ]);

        // $company->setup();

        // $company = factory('App\Company')->create([
        //     'address' => 'Königsworther Straße. 23a',
        //     'bankname' => 'Ethik Bank',
        //     'bic' => 'GENODEF1ESN',
        //     'city' => 'Hannover',
        //     'firstname' => 'Olaf',
        //     'iban' => 'DE54 8309 4495 0003 1128 29',
        //     'lastname' => 'Mußmann',
        //     'name' => 'Dr. Mußmann & Partner',
        //     'phonenumber' => '0511-165 976 020',
        //     'postcode' => 30167,
        // ]);

        // $company = factory('App\Company')->create([
        //     'name' => 'serienguide.tv',
        // ]);

        // $company->setup();
    }
}
