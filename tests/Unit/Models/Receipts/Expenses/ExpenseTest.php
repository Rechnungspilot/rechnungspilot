<?php

namespace Tests\Unit\Models\Receipts\Invoices;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Expense;
use App\Receipts\Order;
use App\Receipts\Term;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Unit\TestCase;

class ExpenseTest extends TestCase
{
    protected $class_name = Expense::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = factory($this->class_name)->create();
        $route_parameter = [
            'expense' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route($this->class_name::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route($this->class_name::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }

}