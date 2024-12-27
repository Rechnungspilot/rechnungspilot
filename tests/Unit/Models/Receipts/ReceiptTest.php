<?php

namespace Tests\Unit\Models\Receipts;

use App\Item;
use App\Unit;
use Tests\TestCase;
use App\Contacts\Contact;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Draft;
use Illuminate\Foundation\Testing\WithFaker;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReceiptTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_its_name_after_been_created()
    {
        $invoice = factory(Invoice::class)->create();
        $this->assertEquals(1, $invoice->number);
        $this->assertEquals(date('Y') . '-0001', $invoice->name);
    }

    /**
     * @test
     */
    public function it_sets_its_name_to_vorlaeufig_when_number_is_zero_after_been_created()
    {
        $invoice = factory(Invoice::class)->create([
            'number' => 0
        ]);
        $this->assertEquals(0, $invoice->number);
        $this->assertEquals('Vorläufig', $invoice->name);
    }

    /**
     * @test
     */
    public function it_gets_the_status_draft_after_it_is_created()
    {
        $invoice = factory(Invoice::class)->create();

        $this->assertEquals(Draft::class, $invoice->latest_status_type);
    }

    /**
     * @test
     */
    public function it_deletes_its_statuses_before_being_deleted()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_can_add_an_item()
    {
        $contact = factory(Contact::class)->create();
        $companyId = $contact->company_id;
        $invoice = factory(Invoice::class)->create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
        ]);
        $unit = factory(Unit::class)->create([
            'company_id' => $companyId
        ]);
        $item = factory(Item::class)->create([
            'company_id' => $companyId,
            'unit_id' => $unit->id,
            'unit_price' => '100',
        ]);

        $receiptItem = $invoice->addItem($item);

        $this->assertCount(1, $invoice->fresh()->items, 'added item');

        $this->assertDatabaseHas('item_receipt', [
            'id' => $receiptItem->id,
            'unit_price' => $item->unit_price,
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_an_item()
    {
        $contact = factory(Contact::class)->create();
        $companyId = $contact->company_id;
        $invoice = factory(Invoice::class)->create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
        ]);
        $unit = factory(Unit::class)->create([
            'company_id' => $companyId
        ]);
        $item = factory(Item::class)->create([
            'company_id' => $companyId,
            'unit_id' => $unit->id,
            'unit_price' => '100',
        ]);

        $receiptItem = $invoice->addItem($item);

        $this->assertCount(1, $invoice->fresh()->items, 'added item');

        $this->assertDatabaseHas('item_receipt', [
            'id' => $receiptItem->id,
            'unit_price' => $item->unit_price,
        ]);

        $invoice->delItem($receiptItem);

        $this->assertCount(0, $invoice->fresh()->items, 'deleted item');

        $this->assertDatabaseMissing('item_receipt', [
            'id' => $receiptItem->id,
        ]);
    }

    /**
     * @test
     */
    public function the_status_send_can_be_added()
    {
        $invoice = factory(Invoice::class)->create();
        $invoice->sendWithoutMail();

        $this->assertEquals(Send::class, $invoice->latest_status_type);
    }

    /**
     * @test
     */
    public function it_can_create_a_x_rechnung_xml()
    {
        $invoice = factory(Invoice::class)->create();
        $document = $invoice->xRechnung();

        $this->assertInstanceOf(ZugferdDocumentBuilder::class, $document);
    }
}
