<?php

use App\Banks\Account;
use App\Company;
use Illuminate\Database\Seeder;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            Account::create([
                'company_id' => $company->id,
                'bank_company_id' => 0,
                'name' => 'Bar',
                'iban' => '',
            ]);

            Account::create([
                'company_id' => $company->id,
                'bank_company_id' => 0,
                'name' => 'Kasse',
                'iban' => '',
            ]);
        }
    }
}
