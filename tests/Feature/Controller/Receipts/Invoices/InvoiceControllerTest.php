<?php

namespace Tests\Feature\Controller\Receipts\Invoices;

use App\Receipts\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\BaseReceiptTests;

class InvoiceControllerTest extends TestCase
{
    use BaseReceiptTests;

    protected $baseRouteName = 'receipt.invoice';
    protected $baseRouteParameter = 'invoice';
    protected $className = Invoice::class;

    /**
     * @test
     */
    public function an_invoice_can_be_set_as_partial()
    {
        $invoice = $this->createReceipt();

        $this->signIn($this->user);

        $response = $this->put(route($this->getBaseRouteName() . '.update', [$this->getBaseRouteParameter() => $invoice->id]), [
            'address' => '',
            'contact_id' => $invoice->contact_id,
            'date' => $invoice->date->format('d.m.Y'),
            'date_due' => $invoice->date_due->format('d.m.Y'),
            'number' => '5',
            'term_id' => $invoice->term_id,
            'text_above' => '',
            'text_below' => '',
            'is_partial' => true,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('receipts', [
            'id' => $invoice->id,
            'address' => null,
            'contact_id' => $invoice->contact_id,
            'date' => $invoice->date->format('Y-m-d H:i:s'),
            'date_due' => $invoice->date_due->format('Y-m-d H:i:s'),
            'number' => 5,
            'term_id' => $invoice->term_id,
            'text_above' => null,
            'text_below' => null,
            'is_partial' => 1,
        ]);
    }
}
