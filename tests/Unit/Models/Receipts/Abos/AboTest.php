<?php

namespace Tests\Unit\Models\Receipts\Abos;

use App\Receipts\Abos\Abo;
use App\Receipts\Abos\Settings;
use App\Receipts\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Unit\TestCase;

class AboTest extends TestCase
{
    protected $class_name = Abo::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = factory($this->class_name)->create();
        $route_parameter = [
            'type' => Invoice::TYPE,
            'subscription' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route($this->class_name::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route($this->class_name::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes, [
            'settings_type' => Invoice::TYPE,
        ]);
    }

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
