<?php

use App\Banks\Account;
use App\Company;
use Illuminate\Database\Seeder;

class BankCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companyId = 1;

        $company = Company::find($companyId);
        $bankCompany = $company->banks()->attach(3351, [
            'username' => '8492435006001',
            'pin' => '320jj',
        ]);

        $bankCompany = $company->banks()->first()->pivot;

        Account::create([
            'company_id' => $companyId,
            'bank_company_id' => $bankCompany->id,
            'name' => 'Tagesgeldkonto',
            'iban' => 'DE91701204008492435014',
        ]);

        Account::create([
            'company_id' => $companyId,
            'bank_company_id' => $bankCompany->id,
            'name' => 'Girokonto',
            'iban' => 'DE16701204008492435006',
        ]);
    }
}
