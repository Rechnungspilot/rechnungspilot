<?php

namespace Tests\Feature\Controller\Receipts\Invoices;

use App\Contacts\Contact;
use App\Item;
use App\Mail\ReceiptSend;
use App\Receipts\Invoice;
use App\Receipts\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class KeepsevenControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_create_view()
    {
        $this->markTestIncomplete();
        $response = $this->get('/rechnungen/keepseven/create')->assertOk();
    }

    /**
     * @test
     */
    public function it_can_create_a_new_invoice()
    {
        $this->markTestIncomplete();
        $this->withoutExceptionHandling();

        Term::setup(1);

        Mail::fake();

        $item = factory(Item::class)->create([
            'id' => 14,
            'company_id' => 1,
            'unit_price' => 1,
        ]);

        $contact = factory(Contact::class)->create([
            'id' => 42,
            'company_id' => 1,
        ]);

        $response = $this->post('/rechnungen/keepseven', ['quantity' => '1,23'])
            ->assertRedirect();

        $this->assertDatabaseHas('receipts', [
            'type' => Invoice::class,
            'company_id' => 1,
            'contact_id' => 42,
        ]);

        $this->assertDatabaseHas('item_receipt', [
            'company_id' => 1,
            'item_id' => 14,
            'quantity' => 1.230000
        ]);

        Mail::assertSent(ReceiptSend::class, 2);
    }
}
