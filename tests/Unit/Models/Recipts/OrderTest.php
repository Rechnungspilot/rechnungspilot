<?php

namespace Tests\Unit\Models\Recipts;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Receipts\Term;
use App\Todos\Todo;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
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
            'type' => Item::TYPE_ITEM,
        ]);

        $this->service = factory(Item::class)->create([
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
            'type' => Quote::class,
        ]);

        $this->fromReceipt = factory(Quote::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $this->term->id,
        ]);

        $this->receiptItem = $this->fromReceipt->addItem($this->item);
        $this->receiptItemService = $this->fromReceipt->addItem($this->service);

        $todos = factory(Todo::class, 3)->create([
            'company_id' => $this->user->company_id,
            'todoable_type' => Receipt::class,
            'todoable_id' => $this->fromReceipt->id,
        ]);

        $this->fromReceipt = $this->fromReceipt->fresh();
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_quote()
    {
        $this->assertCount(3, $this->fromReceipt->todos, 'count todos original receipt');

        $order = Order::from($this->fromReceipt);

        $this->assertDatabaseHas('receipts', [
            'id' => $order->id,
        ]);

        $this->assertCount(0, $this->fromReceipt->fresh()->todos, 'count todos original receipt after');
        $this->assertCount(3, $order->fresh()->todos, 'count todos');

        $this->assertDatabaseHas('todos', [
            'todoable_type' => Receipt::class,
            'todoable_id' => $order->id,
        ]);

        $this->assertCount(2, $order->fresh()->items, 'count receipt items');

        $this->assertDatabaseHas('item_receipt', [
            'item_id' => $this->item->id,
            'receipt_id' => $order->id,
            'receiptable_type' => ReceiptItem::class,
            'receiptable_id' => $this->receiptItem->id,
        ]);
    }
}
