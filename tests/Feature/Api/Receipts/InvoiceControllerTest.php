<?php

namespace Tests\Feature\Api\Receipts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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

        $response = $this->get(route('api.companies.invoices.index', [
            'company' => $this->user->company_id
        ]));
    }

    /**
     * @test
     */
    public function it_can_get_the_invoices_with_an_item_for_a_company()
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

        $response = $this->get(route('api.companies.invoices.index', [
            'company' => $this->user->company_id,
            'item_id' => $item->id,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');

        $invoice->addItem($item);

        $response = $this->get(route('api.companies.invoices.index', [
            'company' => $this->user->company_id,
            'item_id' => $item->id,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data');
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

        $response = $this->post(route('api.companies.invoices.store', [
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
    }
}
