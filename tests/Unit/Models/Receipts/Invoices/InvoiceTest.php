<?php

namespace Tests\Unit\Models\Receipts\Invoices;

use PHPUnit\Framework\Attributes\Test;
use App\Item;
use App\Unit;
use App\Receipts\Term;
use App\Receipts\Order;
use Tests\Unit\TestCase;
use App\Contacts\Contact;
use App\Receipts\Invoice;
use Illuminate\Support\Carbon;

class InvoiceTest extends TestCase
{
    protected $class_name = Invoice::class;

    private Unit $unit;
    private Item $item;
    private Contact $contact;
    private Term $term;
    private Invoice $fromReceipt;

    protected function setUp() : void
    {
        parent::setUp();

        $this->unit = factory(Unit::class)->create([
            'company_id' => $this->company->id,
        ]);
        $this->item = factory(Item::class)->create([
            'company_id' => $this->company->id,
            'unit_id' => $this->unit->id,
            'type' => Item::TYPE_SERVICE,
        ]);
        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->company->id,
        ]);

        $this->term = factory(Term::class)->create([
            'company_id' => $this->company->id,
            'default' => true,
        ]);

        $this->fromReceipt = factory(Invoice::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $this->term->id,
        ]);

        $this->fromReceipt->addItem($this->item);
        $this->fromReceipt->addItem($this->item);

        $this->fromReceipt = $this->fromReceipt->fresh();
    }

    /**
     */
    #[Test]
    public function it_has_model_paths()
    {
        $model = factory($this->class_name)->create();
        $route_parameter = [
            'invoice' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route($this->class_name::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route($this->class_name::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }

    /**
     */
    #[Test]
    public function it_has_items()
    {
        $this->assertCount(2, $this->fromReceipt->fresh()->items);
    }

    /**
     */
    #[Test]
    public function it_can_be_created_from_another_receipt()
    {
        $invoice = Invoice::from($this->fromReceipt);

        $this->assertCount(2, $invoice->fresh()->items);
    }

    /**
     */
    #[Test]
    public function it_can_be_created_from_another_receipt_with_date_and_date_due()
    {
        $now = Carbon::parse('2023-01-10 12:00:00');
        $this->travelTo($now);

        $last_month = $now->clone()->subMonth();
        $start_of_last_month = $last_month->clone()->startOfMonth();
        $end_of_last_month = $last_month->clone()->endOfMonth();

        $invoice_last_year = factory(Invoice::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $this->term->id,
            'number' => 5,
            'date' => $start_of_last_month,
            'date_due' => $end_of_last_month,
        ]);

        $invoice = Invoice::from($invoice_last_year, [
            'date' => $end_of_last_month,
            'date_due' => $end_of_last_month,
        ]);

        $this->assertEquals($invoice->number, 6);
    }

    /**
     */
    #[Test]
    public function it_can_be_created_from_an_order()
    {
        $term = factory(Term::class)->create([
            'company_id' => $this->company->id,
            'default' => true,
            'type' => Order::class
        ]);

        $order = factory(Order::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $term->id,
        ]);

        $invoice = Invoice::from($order);

        $this->assertDatabaseHas('receipts', [
            'id' => $invoice->id,
            'receipt_id' => $order->id,
        ]);
    }

    /**
     */
    #[Test]
    public function a_credit_can_be_created_from_an_invoice()
    {
        $invoice = Invoice::from($this->fromReceipt, [
            'credit' => true
        ]);

        $this->assertCount(2, $invoice->fresh()->items);
    }

    /**
     */
    #[Test]
    public function selected_receipt_items_can_be_added_to_an_existing_invoice_from_another_receipt()
    {
        $invoice = Invoice::from($this->fromReceipt);

        $this->assertCount(2, $invoice->fresh()->items);

        $invoice = Invoice::from($this->fromReceipt, [
            'receipt_id' => $invoice->id,
        ]);

        $this->assertCount(4, $invoice->fresh()->items);
    }

    /**
     */
    #[Test]
    public function receipt_items_can_be_added_to_an_existing_invoice_from_another_receipt()
    {
        $invoice = Invoice::from($this->fromReceipt, [
            'receipt_item_ids' => [
                $this->fromReceipt->items->first()->id,
            ],
        ]);

        $this->assertCount(1, $invoice->fresh()->items);
    }


}
