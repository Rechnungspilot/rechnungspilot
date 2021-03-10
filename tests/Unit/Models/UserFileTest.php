<?php

namespace Tests\Unit\Models;

use App\Userfile;
use Tests\Unit\TestCase;

class UserFileTest extends TestCase
{
    protected $class_name = Userfile::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = factory($this->class_name)->create();
        $route_parameter = [
            'userfile' => $model->id,
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
    public function it_deletes_the_file_on_deleting()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_has_a_path()
    {
        // folder/name.extension
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_has_an_url()
    {
        // http://dateien.rechnungspilot.de/
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
