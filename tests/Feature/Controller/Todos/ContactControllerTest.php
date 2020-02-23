<?php

namespace Tests\Feature\Controller\Todos;

use App\Contacts\Contact;
use App\Todos\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    protected $baseRouteName = 'todo.contacts';
    protected $className = Todo::class;

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $todo = factory($this->className)->create();
        $contact = factory(Contact::class)->create([
            'company_id' => $todo->company_id,
        ]);

        $actions = [
            'index' => [],
            'store' => ['todo' => $todo->id, 'contact' => $contact->id],
            'destroy' => ['todo' => $todo->id, 'contact' => $contact->id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_todos_of_an_other_company()
    {
        $todoOfADifferentCompany = factory($this->className)->create();
        $contactOfADifferentCompany = factory(Contact::class)->create([
            'company_id' => $todoOfADifferentCompany->company_id,
        ]);

        $this->signIn();

        $parameters = [
            'contact' => $contactOfADifferentCompany->id,
            'todo' => $todoOfADifferentCompany->id,
        ];

        $this->a_user_of_a_different_company_gets_a_404('store', 'post', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', $parameters);

        $response = $this->json('get', route($this->baseRouteName . '.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_contacts()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $contacts = factory(Contact::class, 3)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->assertCount(3, Contact::all());

        $response = $this->get(route($this->baseRouteName . '.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function a_user_can_attach_a_contact_once()
    {
        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);
        $contact = factory(Contact::class)->create([
            'company_id' => $todo->company_id,
        ]);

        $this->signIn($this->user);

        $response = $this->json('post', route($this->baseRouteName . '.store', ['todo' => $todo->id, 'contact' => $contact->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $contact->id,
            ]);

        $this->assertCount(1, $todo->fresh()->contacts);

        $this->assertDatabaseHas('contact_todo', [
            'contact_id' => $contact->id,
            'todo_id' => $todo->id,
        ]);

        $response = $this->json('post', route($this->baseRouteName . '.store', ['todo' => $todo->id, 'contact' => $contact->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([]);

        $this->assertCount(1, $todo->fresh()->contacts);
    }

    /**
     * @test
     */
    public function a_user_can_dettach_a_contact()
    {
        $this->withoutExceptionHandling();

        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);
        $contact = factory(Contact::class)->create([
            'company_id' => $todo->company_id,
        ]);

        $this->signIn($this->user);

        $this->assertCount(0, $todo->fresh()->contacts);

        $todo->attach($contact);

        $this->assertCount(1, $todo->fresh()->contacts);

        $this->assertDatabaseHas('contact_todo', [
            'contact_id' => $contact->id,
            'todo_id' => $todo->id,
        ]);

        $response = $this->json('delete', route($this->baseRouteName . '.destroy', ['todo' => $todo->id, 'contact' => $contact->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $todo->fresh()->contacts);

        $this->assertDatabaseMissing('contact_todo', [
            'contact_id' => $contact->id,
            'todo_id' => $todo->id,
        ]);
    }
}
