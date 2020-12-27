<?php

namespace Tests\Unit\Models\Banks;

use App\Banks\Bank;
use App\Banks\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_accounts()
    {
        $bank = Bank::create([
            'id' => 27,
            'blz' => '12030000',
            'name' => 'Deutsche Kreditbank Berlin (DKB) AG',
            'city' => 'Berlin ',
            'url' => 'https://banking-dkb.s-fints-pt-dkb.de/fints30',
        ]);

        $company = factory(\App\Company::class)->create();

        $bank_company = Company::create([
            'bank_id' => $bank->id,
            'company_id' => $company->id,
            'username' => '1059268977_p',
            'pin' => 'C7n20'
        ]);
        $bank_company->load([
            'bank',
        ]);

        dd($bank, $bank_company, $bank_company->bank);

        $accounts = $bank_company->getSepaAccounts();
        dump($accounts);
    }
}
