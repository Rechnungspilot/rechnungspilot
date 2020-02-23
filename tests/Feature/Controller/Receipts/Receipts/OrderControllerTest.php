<?php

namespace Tests\Feature\Controller\Receipts\Receipts;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Term;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
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

        $this->receipt = factory(Invoice::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $this->term->id,
        ]);

        $this->receipt->addItem($this->item);
        $this->receipt->addItem($this->item);

        $this->receipt = $this->receipt->fresh();

        $term = factory(Term::class)->create([
            'company_id' => $this->company->id,
            'default' => true,
            'type' => Order::class
        ]);

        $this->order = factory(Order::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $term->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_attach_an_order()
    {
        $this->signIn();

        $response = $this->putJson(route('receipt.receipt.order.update', ['receipt' => $this->receipt->id]), [
            'receipt_id' => $this->order->id,
        ]);

        $this->assertDatabaseHas('receipts', [
            'id' => $this->receipt->id,
            'receipt_id' => $this->order->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_detach_an_order()
    {
        $this->signIn();

        $this->receipt->update([
            'receipt_id' => $this->order->id,
        ]);

        $this->assertDatabaseHas('receipts', [
            'id' => $this->receipt->id,
            'receipt_id' => $this->order->id,
        ]);

        $response = $this->putJson(route('receipt.receipt.order.update', ['receipt' => $this->receipt->id]), [
            'receipt_id' => null,
        ]);

        $this->assertDatabaseHas('receipts', [
            'id' => $this->receipt->id,
            'receipt_id' => null,
        ]);
    }
}
