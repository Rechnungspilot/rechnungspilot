<?php

namespace Tests\Unit\Models\Items;

use App\Unit;
use Tests\Unit\TestCase;

class UnitTest extends TestCase
{
    protected $class_name = Unit::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = factory($this->class_name)->create();
        $route_parameter = [
            'unit' => $model->id,
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
    public function it_has_labels()
    {
        $this->assertEquals('Einheiten', $this->class_name::label());
        $this->assertEquals('Einheiten', $this->class_name::label(2));
        $this->assertEquals('Einheit', $this->class_name::label(1));
    }
}
