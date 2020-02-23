<?php

namespace Tests\Unit\Models\Receipts\Abos;

use App\Receipts\Abos\Abo;
use App\Receipts\Abos\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AboTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_settings_after_it_is_created()
    {
        $abo = factory(Abo::class)->create();
        $today = today();

        $this->assertCount(1, Settings::where('abo_id', $abo->id)->get());

        $this->assertDatabaseHas('abo_settings', [
            'abo_id' => $abo->id,
            'interval_value' => 1,
            'interval_unit' => 'months',
            'start_at' => $today,
            'next_at' => $today,
        ]);
    }

    /**
     * @test
     */
    public function it_deletes_its_statuses_and_settings_on_deleting()
    {
        $abo = factory(Abo::class)->create();

        $this->assertDatabaseHas('receipt_statuses', [
            'receipt_id' => $abo->id,
        ]);

        $this->assertDatabaseHas('abo_settings', [
            'abo_id' => $abo->id,
        ]);

        $abo->delete();

        $this->assertDatabaseMissing('receipt_statuses', [
            'receipt_id' => $abo->id,
        ]);

        $this->assertDatabaseMissing('abo_settings', [
            'abo_id' => $abo->id,
        ]);
    }
}
