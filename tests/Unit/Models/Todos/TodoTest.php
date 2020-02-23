<?php

namespace Tests\Unit\Models\Todos;

use App\Contacts\Contact;
use App\Item;
use App\Todos\Todo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_creator()
    {
        $creator = factory(User::class)->create();
        $todo = factory(Todo::class)->create([
            'company_id' => $creator->company_id,
            'creator_id' => $creator->id,
        ]);

        $this->assertEquals($creator->fresh(), $todo->creator);
    }

    /**
     * @test
     */
    public function it_belongs_to_a_teammember()
    {
        $creator = factory(User::class)->create();
        $todo = factory(Todo::class)->create([
            'company_id' => $creator->company_id,
            'creator_id' => $creator->id,
        ]);

        $this->assertEquals(null, $todo->teamMember);

        $todo->assignTo($creator)->save();

        $this->assertEquals($creator->fresh(), $todo->fresh()->teamMember);
    }

    /**
     * @test
     */
    public function it_belongs_to_an_item()
    {
        $item = factory(Item::class)->create();
        $todo = factory(Todo::class)->create([
            'company_id' => $item->company_id
        ]);

        $this->assertEquals(null, $todo->item);

        $todo->addItem($item)->save();

        $this->assertEquals($item->fresh(), $todo->fresh()->item);
    }

    /**
     * @test
     */
    public function it_can_attach_and_detach_contacts()
    {
        $contacts_count = 1;
        $user = $this->signIn();
        $company = $user->company;
        $contacts = factory(Contact::class, $contacts_count)->create([
            'company_id' => $company->id,
        ]);
        $todo = factory(Todo::class)->create([
            'company_id' => $company->id,
        ]);

        $this->assertCount(0, $todo->contacts);

        foreach ($contacts as $key => $contact) {
            $attached = $todo->attach($contact);
            $this->assertEquals($contact->id, $attached->id);
        }

        $this->assertCount($contacts_count, $todo->fresh()->contacts);

        foreach ($contacts as $key => $contact) {
            $todo->detach($contact);
        }

        $this->assertCount(0, $todo->contacts->fresh());
    }

    /**
     * @test
     */
    public function it_can_be_completed()
    {
        $this->signIn();

        $todo = factory(Todo::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'completer_id' => null,
            'completed_at' => null,
            'completed' => false,
        ]);

        $todo->complete()
            ->save();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'completer_id' => $this->user->id,
            'completed_at' => now(),
            'completed' => true,
        ]);
    }

    /**
     * @test
     */
    public function it_can_be_incompleted()
    {
        $this->signIn();

        $todo = factory(Todo::class)->create([
            'company_id' => $this->user->company_id,
            'completer_id' => $this->user->id,
            'completed_at' => now(),
            'completed' => true,
        ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'completer_id' => $this->user->id,
            'completed_at' => now(),
            'completed' => true,
        ]);

        $todo->incomplete()
            ->save();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'completer_id' => null,
            'completed_at' => null,
            'completed' => false,
        ]);
    }
}
