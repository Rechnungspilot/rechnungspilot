<?php

namespace Tests\Unit\Models\Receipts\Invoices;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Term;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Unit\TestCase;

class InvoiceTest extends TestCase
{
    protected $class_name = Invoice::class;

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
     * @test
     */
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
     * @test
     */
    public function it_has_items()
    {
        $this->assertCount(2, $this->fromReceipt->fresh()->items);
    }

    /**
     * @test
     */
    public function it_can_be_created_from_another_receipt()
    {
        $invoice = Invoice::from($this->fromReceipt);

        $this->assertCount(2, $invoice->fresh()->items);
    }

    /**
     * @test
     */
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
     * @test
     */
    public function a_credit_can_be_created_from_an_invoice()
    {
        $invoice = Invoice::from($this->fromReceipt, [
            'credit' => true
        ]);

        $this->assertCount(2, $invoice->fresh()->items);
    }

    /**
     * @test
     */
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
     * @test
     */
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
