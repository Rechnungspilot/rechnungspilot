<?php

namespace Tests\Feature\Controller\Receipts\Receipts;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Receipts\Term;
use App\Todos\Todo;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    protected $baseRouteName = 'receipt.receipt.todo';
    protected $baseViewPath = 'todo.task';
    protected $className = Todo::class;

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

        $this->receipt = factory(Quote::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'term_id' => $this->term->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_todos_from_a_receipt()
    {
        $todos = factory($this->className, 3)->create([
            'company_id' => $this->user->company_id,
            'todoable_type' => Receipt::class,
            'todoable_id' => $this->receipt->id,
        ]);

        $this->signIn();

        $this->getCollection(['type' => 'belege', 'model' => $this->receipt->id]);
    }
}
