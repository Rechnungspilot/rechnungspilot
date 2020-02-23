<?php

namespace Tests\Feature\Controller\Todos;

use App\Todos\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    protected $baseRouteName = 'todo';
    protected $baseViewPath = 'todo.task';
    protected $className = Todo::class;

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['todo' => $id],
            'edit' => ['todo' => $id],
            'update' => ['todo' => $id],
            'destroy' => ['todo' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_todos_of_an_other_company()
    {
        $modelOfADifferentCompany = factory($this->className)->create();

        $this->a_user_can_not_see_things_from_a_different_company(['todo' => $modelOfADifferentCompany->id]);

        $response = $this->json('get', route($this->baseRouteName . '.index'))
            ->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse()
            ->assertViewIs($this->baseViewPath . '.index')
            ->assertViewHas('tags')
            ->assertViewHas('team');
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_items()
    {
        factory($this->className, 3)->create([
            'company_id' => $this->user->company_id
        ]);

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_get_the_raw_models()
    {

    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->signIn();

        $response = $this->post(route($this->baseRouteName . '.store'));
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('todos', [
            'id' => 1,
            'company_id' => $this->user->company_id,
            'priority' => Todo::DEFAULT_PRIORITY,
            'creator_id' => $this->user->id,
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), []);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'priority' => Todo::DEFAULT_PRIORITY,
                'creator_id' => $this->user->id,
                'user_id' => $this->user->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->getShowViewResponse(['todo' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas($this->baseRouteName)
            ->assertViewHas('users');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->getEditViewResponse(['todo' => $model->id])
            ->assertViewIs($this->baseViewPath . '.edit')
            ->assertViewHas('users');
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        $this->signIn($this->user);

        $startAt = now();
        $endAt = now()->addHours(2);

        $response = $this->put(route($this->baseRouteName . '.update', ['todo' => $model->id]), [
            'description' => '',
            'name' => $model->name . ' updated',
            'start_at' => $startAt,
            'end_at' => $endAt,
            'item_id' => null,
            'note' => '',
            'user_id' => $model->user_id,
            'priority' => Todo::PRIORITY_LOW,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect($model->path);

        $this->assertDatabaseHas('todos', [
            'id' => $model->id,
            'description' => null,
            'name' => $model->name . ' updated',
            'start_at' => $startAt,
            'end_at' => $endAt,
            'item_id' => null,
            'note' => null,
            'user_id' => $model->user_id,
            'priority' => Todo::PRIORITY_LOW,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model_if_it_is_deletable()
    {
        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->deleteModel($model, ['todo' => $model->id])
            ->assertRedirect(route($this->baseRouteName . '.index'));
    }
}
