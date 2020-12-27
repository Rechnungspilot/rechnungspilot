<?php

namespace Tests\Feature\Controller\Todos;

use App\Todos\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CompletedControllerTest extends TestCase
{
    protected $baseRouteName = 'todo.completed';
    protected $className = Todo::class;

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'store' => ['todo' => $id],
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

        $this->signIn();

        $parameters = ['todo' => $modelOfADifferentCompany->id];

        $this->a_user_of_a_different_company_gets_a_404('store', 'post', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', $parameters);
    }

    /**
     * @test
     */
    public function a_user_can_complete_a_model()
    {
        $this->signIn();

        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id
        ]);

        $now = now()->format('Y-m-d H:i:s');
        $response = $this->post(route($this->baseRouteName . '.store', ['todo' => $todo->id]));
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('todos', [
            'id' => 1,
            'company_id' => $this->user->company_id,
            'completer_id' => $this->user->id,
            'completed_at' => $now,
            'completed' => true,
        ]);

        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store', ['todo' => $todo->id]), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'completer_id' => $this->user->id,
                'completed' => true,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_incomplete_a_model()
    {
        $this->signIn();

        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id,
            'completer_id' => $this->user->id,
            'completed_at' => now(),
            'completed' => true,
        ]);

        $response = $this->delete(route($this->baseRouteName . '.destroy', ['todo' => $todo->id]));
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'company_id' => $this->user->company_id,
            'completer_id' => null,
            'completed_at' => null,
            'completed' => false,
        ]);

        $todo = factory($this->className)->create([
            'company_id' => $this->user->company_id,
            'completer_id' => $this->user->id,
            'completed_at' => now(),
            'completed' => true,
        ]);

        $response = $this->json('DELETE', route($this->baseRouteName . '.destroy', ['todo' => $todo->id]), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'id' => $todo->id,
                'company_id' => $this->user->company_id,
                'completer_id' => null,
                'completed_at' => null,
                'completed' => false,
            ]);
    }
}
