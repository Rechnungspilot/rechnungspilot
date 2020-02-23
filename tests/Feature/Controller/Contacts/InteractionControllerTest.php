<?php

namespace Tests\Feature\Controller\Contacts;

use App\Contacts\Contact;
use App\Contacts\Interaction;
use App\Contacts\InteractionType;
use App\Contacts\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class InteractionControllerTest extends TestCase
{
    use WithFaker;

    protected $baseRouteName = 'interaction';
    protected $baseViewPath = 'contact.interaction';
    protected $className = Interaction::class;

    protected $contact;

    protected function setUp() : void
    {
        parent::setUp();

        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company,
        ]);

        $this->person = factory(Person::class)->create([
            'company_id' => $this->contact->company_id,
            'contact_id' => $this->contact->id,
        ]);

        $this->interactionType = factory(InteractionType::class)->create([
            'company_id' => $this->user->company,
        ]);
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => ['type' => 'kontakte', 'model' => $this->contact->id],
            'store' => ['type' => 'kontakte', 'model' => $this->contact->id],
            'show' => ['transaction' => $id],
            'edit' => ['transaction' => $id],
            'update' => ['transaction' => $id],
            'destroy' => ['transaction' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_todos_of_an_other_company()
    {
        $modelOfADifferentCompany = factory($this->className)->create();

        $this->a_user_can_not_see_things_from_a_different_company(['transaction' => $modelOfADifferentCompany->id]);

        $response = $this->json('get', route($this->baseRouteName . '.index', ['type' => 'kontakte', 'model' => $this->contact->id]))
            ->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_items()
    {
        $models = factory($this->className, 3)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
            'interaction_type_id' => $this->interactionType->id,
        ]);

        $this->getPaginatedCollection([
            'type' => 'kontakte',
            'model' => $this->contact->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createInteraction();

        $this->getShowViewResponse(['interaction' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createInteraction();

        $this->getEditViewResponse(['interaction' => $model->id])
            ->assertViewIs($this->baseViewPath . '.edit')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->signIn();

        $response = $this->post(route($this->baseRouteName . '.store', [
            'type' => 'kontakte',
            'model' => $this->contact->id,
        ]));
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('interactions', [
            'id' => 1,
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson(route($this->baseRouteName . '.store', [
            'type' => 'kontakte',
            'model' => $this->contact->id,
        ]));
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name', 'text'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'contact_id' => $this->contact->id,
                'user_id' => $this->user->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createInteraction();

        $this->signIn($this->user);

        $now = now();
        $now->second = 0;
        $name = $this->faker->sentence;
        $text = $this->faker->paragraph;
        $response = $this->put(route($this->baseRouteName . '.update', ['interaction' => $model->id]), [
            'interaction_type_id' => $this->interactionType->id,
            'person_id' => 0,
            'at' => $now->format('d.m.Y H:i'),
            'name' => $name,
            'text' => $text,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect($model->path);

        $this->assertDatabaseHas('interactions', [
            'id' => $model->id,
            'person_id' => null,
            'at' => $now->format('Y-m-d H:i:s'),
            'name' => $name,
            'text' => $text,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model_if_it_is_deletable()
    {
        $model = $this->createInteraction();

        $this->deleteModel($model, ['interaction' => $model->id])
            ->assertRedirect();
    }

    protected function createInteraction() : Interaction
    {
        return factory($this->className)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
            'person_id' => $this->person->id,
            'interaction_type_id' => $this->interactionType->id,
            'user_id' => $this->user->id,
        ]);
    }
}
