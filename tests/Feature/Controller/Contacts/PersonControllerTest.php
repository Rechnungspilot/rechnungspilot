<?php

namespace Tests\Feature\Controller\Contacts;

use App\Contacts\Contact;
use App\Contacts\Person;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    protected $baseRouteName = 'contact.person';
    protected $contact;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = $this->createPerson()->id;

        $actions = [
            'index' => ['contact' => $this->contact->id],
            'store' => ['contact' => $this->contact->id],
            'edit' => ['contact' => $this->contact->id, 'person' => $id],
            'update' => ['contact' => $this->contact->id, 'person' => $id],
            'destroy' => ['contact' => $this->contact->id, 'person' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_persons_of_an_other_company()
    {
        $person = factory(Person::class)->create();

        $this->signIn($this->user);

        $response = $this->json('get', route($this->baseRouteName . '.index', ['contact' => $person->contact_id]))
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
                'exception',
                'file',
                'line',
                'trace',
            ]);
        // $this->a_user_of_a_different_company_gets_a_404('show', 'get', , ['contact' => $person->contact_id, 'person' => $person->id]);

        $this->a_user_of_a_different_company_gets_a_404('edit', 'get', ['person' => $person->id]);

        $this->a_user_of_a_different_company_gets_a_404('update', 'put', ['person' => $person->id]);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', ['person' => $person->id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_people()
    {
        $this->signIn($this->user);

        $persons = factory(Person::class, 3)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
        ]);

        $response = $this->json('get', route($this->baseRouteName . '.index', ['contact' => $this->contact->id]), [
            'searchtext' => '',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_person()
    {
        $this->signIn($this->user);

        $data = [
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
            'default_invoice' => 0,
            'default_quote' => 0,
            'firstname' => 'Ansprechpartner',
            'lastname' => 'Neuer',
        ];

        $response = $this->json('post', route($this->baseRouteName . '.store', ['contact' => $this->contact->id]));
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);

        $this->assertDatabaseHas('people', $data);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $this->signIn($this->user);

        $person = $this->createPerson();

        $response = $this->get(route($this->baseRouteName . '.edit', ['contact' => $this->contact->id, 'person' => $person->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('contact.person.edit')
            ->assertViewHas('person');
    }

    /**
     * @test
     */
    public function a_user_can_update_a_person()
    {
        $person = $this->createPerson();

        $data = [
            'email' => $person->email,
            'firstname' => $person->firstname . ' updated',
            'function' => $person->function . ' updated',
            'lastname' => $person->phonenumber . ' updated',
            'mobilenumber' => $person->phonenumber . ' updated',
            'phonenumber' => $person->phonenumber . ' updated',
            'title' => '',
        ];

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['person' => $person->id]), $data);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $data['title'] = null;
        $this->assertDatabaseHas('people', $data + [
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
            'default_invoice' => 0,
            'default_quote' => 0,
            'id' => $person->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_person()
    {
        $this->signIn($this->user);

        $this->assertCount(0, $this->contact->people);

        $person = $this->createPerson();

        $this->assertCount(1, $this->contact->fresh()->people);

        $response = $this->delete(route($this->baseRouteName . '.destroy', ['person' => $person->id]));

        $this->assertCount(0, $this->contact->fresh()->people);

        $this->assertDatabaseMissing('people', [
            'id', $person->id
        ]);
    }

    protected function createPerson(array $attributes = []) : Person
    {
        return factory(Person::class)->create(array_merge([
            'contact_id' => $this->contact->id,
        ], $attributes));
    }
}