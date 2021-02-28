<?php

namespace Tests\Feature\Api\Receipts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_the_invoices_for_a_company()
    {
        $this->signIn();

        $contact = factory(\App\Contacts\Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $invoice = factory(\App\Receipts\Invoice::class)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $contact->id,
        ]);

        $response = $this->get(route('companies.invoices.index', [
            'company' => $this->user->company_id
        ]));

        dump($response->json());
    }

    /**
     * @test
     */
    public function it_can_create_an_invoices_for_a_company()
    {
        $this->signIn();

        $contact = factory(\App\Contacts\Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $item = factory(\App\Item::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $invoice = factory(\App\Receipts\Invoice::class)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $contact->id,
        ]);

        $response = $this->post(route('companies.invoices.store', [
            'company' => $this->user->company_id
        ]), [
            'receipt' => [
                'number' => 0,
            ],
            'contact' => [
                'id' => $contact->id,
            ],
            'items' => [
                0 => [
                    'item' => [
                        'id' => $item->id,
                    ],
                    'line' => [
                        'quantity' => 1.23,
                        'unit_price' => 2.34,
                    ],
                ],
            ],
        ]);

        dump($response->json());
    }
}
