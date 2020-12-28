<?php

namespace Tests\Unit\Items;

use App\Company;
use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_default_name_if_none_is_given_on_creating()
    {
        $unit = factory(Unit::class)->create();

        $item = Item::create([
            'company_id' => $unit->company_id,
            'unit_id' => $unit->id,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => Item::DEFAULT_NAME,
        ]);
    }

    /**
     * @test
     */
    public function it_gets_its_gross_attribute()
    {
        $item = factory(Item::class)->create([
            'unit_price' => '1,23',
            'tax' => 0.1,
        ]);

        $this->assertEquals(1.353, $item->gross);
    }

    /**
     * @test
     */
    public function it_gets_its_gross_attribute_in_cents()
    {
        $item = factory(Item::class)->create([
            'unit_price' => '1,23',
            'tax' => 0.1,
        ]);

        $this->assertEquals(135.3, $item->grossInCents);
    }

    /**
     * @test
     */
    public function it_gets_the_formated_duration_hour_attribute()
    {
        $item = factory(Item::class)->create([
            'duration' => 9000,
        ]);

        $this->assertEquals('02', $item->durationHour);
    }

    /**
     * @test
     */
    public function it_sets_the_duration_from_valid_hours_and_minutes()
    {
        $item = factory(Item::class)->create();

        $hours = 2;
        $minutes = 30;

        $item->setDuration($hours, $minutes);

        $this->assertEquals(9000, $item->duration);

        $hours = 0;
        $minutes = 0;

        $item->setDuration($hours, $minutes);

        $this->assertEquals(0, $item->duration);

        $this->expectException(\InvalidArgumentException::class);

        $hours = -1;
        $minutes = 0;

        $item->setDuration($hours, $minutes);

        $this->assertEquals(0, $item->duration);

        $hours = 1;
        $minutes = 76;

        $item->setDuration($hours, $minutes);

        $this->assertEquals(0, $item->duration);
    }

    /**
     * @test
     */
    public function it_gets_the_formated_duration_minute_attribute()
    {
        $item = factory(Item::class)->create([
            'duration' => 9000,
        ]);

        $this->assertEquals('30', $item->durationMinute);
    }

    /**
     * @test
     */
    public function it_belongs_to_a_company()
    {
        $item = factory(Item::class)->create();

        $this->assertInstanceOf(Company::class, $item->company);
    }

    /**
     * @test
     */
    public function it_has_many_prices()
    {
        $item = factory(Item::class)->create();

        $this->assertCount(1, $item->prices);
    }

    /**
     * @test
     */
    public function it_checks_if_it_is_deletable()
    {
        $item = factory(Item::class)->create();
        $companyId = $item->company_id;
        $contact = factory(Contact::class)->create([
            'company_id' => $companyId,
        ]);
        $invoice = factory(Invoice::class)->create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
        ]);

        $this->assertTrue($item->isDeletable());

        $receiptItem = $invoice->addItem($item);
        $this->assertCount(1, $item->receipts, 'item has 1 receipt');

        $this->assertFalse($item->isDeletable(), 'item has invoices');

        $invoice->delItem($receiptItem);
        $this->assertCount(0, $item->fresh()->receipts, 'item deleted from receipt');

        $this->assertTrue($item->isDeletable(), 'item has no invoices');
    }

    /**
     * @test
     */
    public function it_can_calulate_its_revenue()
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

        $item->calculateRevenue();

        $this->assertEquals(0, $item->revenue, 'no items on invoice');

        $invoice->addItem($item);

        $item->calculateRevenue();

        $this->assertEquals(0, $item->revenue, 'invoice not send');

        $invoice->sendWithoutMail();

        $item->calculateRevenue();

        $this->assertEquals($item->grossInCents, $item->revenue, 'invoice send');
    }

    /**
     * @test
     */
    public function it_adds_a_price_if_item_prices_are_changed_on_updating()
    {
        $item = factory(Item::class)->create();

        $this->assertCount(1, $item->prices, 'after create');

        $item->update([
            'unit_cost' => $item->unit_cost,
            'unit_price' => $item->unit_price,
        ]);

        $this->assertCount(1, $item->fresh()->prices, 'set same values');

        $item->update([
            'unit_cost' => '1',
            'unit_price' => $item->unit_price,
        ]);

        $this->assertCount(2, $item->fresh()->prices, 'set new unit_price values');

        $item->update([
            'unit_cost' => $item->unit_cost,
            'unit_price' => '1',
        ]);

        $this->assertCount(3, $item->fresh()->prices, 'set new unit_cost values');

        $item->update([
            'unit_cost' => $item->unit_price,
            'unit_price' => $item->unit_price,
        ]);

        $this->assertCount(3, $item->fresh()->prices, 'set same values again');
    }

    /**
     * @test
     */
    public function it_knows_if_the_unit_price_is_updated()
    {
        $item = factory(Item::class)->create();
        $this->assertFalse($item->isDirty('unit_price'), 'Not updated');

        $item->unit_price = 0;

        $this->assertFalse($item->isDirty('unit_price'), 'Updated to the same value');

        $item->unit_price = '1,23';

        $this->assertTrue($item->isDirty('unit_price'), 'new value');

        $item->save();

        $item = Item::find($item->id);

        $item->unit_price = '1,23';

        $this->assertFalse($item->isDirty('unit_price'), 'Updated to the same value again');

        $item->unit_price = '2,23';

        $this->assertTrue($item->isDirty('unit_price'), 'new value');
    }

    /**
     * @test
     */
    public function it_knows_if_the_unit_cost_is_updated()
    {
        $item = factory(Item::class)->create();
        $this->assertFalse($item->isDirty('unit_cost'), 'Not updated');

        $item->unit_cost = 0;

        $this->assertFalse($item->isDirty('unit_cost'), 'Updated to the same value');

        $item->unit_cost = '1,23';

        $this->assertTrue($item->isDirty('unit_cost'), 'new value');

        $item->save();

        $item = Item::find($item->id);

        $item->unit_cost = '1,23';

        $this->assertFalse($item->isDirty('unit_cost'), 'Updated to the same value again');

        $item->unit_cost = '2,23';

        $this->assertTrue($item->isDirty('unit_cost'), 'new value');
    }

    /**
     * @test
     */
    public function it_has_labels()
    {
        $this->assertEquals('Artikel', Item::label());
        $this->assertEquals('Artikel', Item::label(1));
        $this->assertEquals('Artikel', Item::label(2));
    }
}
