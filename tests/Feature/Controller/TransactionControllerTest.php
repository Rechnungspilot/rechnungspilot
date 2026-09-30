<?php

namespace Tests\Feature\Controller;

use App\Banks\Account;
use App\Company;
use App\Transaction;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    #[Test]
    public function index_only_returns_transactions_from_the_current_company()
    {
        $ownTransaction = $this->createTransaction($this->company, [
            'reference' => 'own-transaction',
        ]);
        $otherCompany = factory(Company::class)->create();
        $otherCompany->setup();
        $otherTransaction = $this->createTransaction($otherCompany, [
            'reference' => 'other-company-transaction',
        ]);

        $this->signIn();

        $response = $this->getJson(route('transactions.index', ['perPage' => 25]));

        $response->assertOk()
            ->assertJsonFragment(['id' => $ownTransaction->id])
            ->assertJsonMissing(['id' => $otherTransaction->id])
            ->assertJsonMissing(['reference' => 'other-company-transaction']);
    }

    #[Test]
    public function account_filter_cannot_expose_transactions_from_another_company()
    {
        $otherCompany = factory(Company::class)->create();
        $otherCompany->setup();
        $otherTransaction = $this->createTransaction($otherCompany);

        $this->signIn();

        $this->getJson(route('transactions.index', [
            'perPage' => 25,
            'account_id' => $otherTransaction->account_id,
        ]))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function user_cannot_update_a_transaction_from_another_company()
    {
        $otherCompany = factory(Company::class)->create();
        $otherCompany->setup();
        $transaction = $this->createTransaction($otherCompany);
        $originalAmount = $transaction->amount;

        $this->signIn();

        $this->putJson(route('transactions.update', ['transaction' => $transaction->id]), [
            'amount' => '99,99',
            'date' => '30.09.2026',
            'receipt_ids' => [],
        ])->assertNotFound();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'company_id' => $otherCompany->id,
            'amount' => $originalAmount,
        ]);
    }

    #[Test]
    public function user_can_update_a_transaction_from_their_own_company()
    {
        $transaction = $this->createTransaction($this->company);

        $this->signIn();

        $this->putJson(route('transactions.update', ['transaction' => $transaction->id]), [
            'amount' => '12,34',
            'date' => '30.09.2026',
            // The current endpoint requires a non-empty array and treats null entries
            // as deselected receipts.
            'receipt_ids' => [999999 => null],
        ])->assertOk()
            ->assertJsonFragment([
                'id' => $transaction->id,
                'amount' => 1234,
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'company_id' => $this->company->id,
            'amount' => 1234,
        ]);
        $this->assertSame(
            '2026-09-30',
            Transaction::withoutGlobalScopes()->findOrFail($transaction->id)->date->toDateString()
        );
    }

    private function createTransaction(Company $company, array $attributes = []): Transaction
    {
        $account = Account::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->firstOrFail();

        return Transaction::withoutGlobalScopes()->create(array_merge([
            'company_id' => $company->id,
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 1000,
            'date' => '2026-09-29',
            'reference' => '',
            'text' => '',
            'name' => 'Test transaction',
            'iban' => 'DE0012345678',
        ], $attributes));
    }
}
