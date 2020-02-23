<?php

namespace Tests;

use App\User;
use App\Userfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $company;
    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->company = $this->user->company;
    }

    public function signIn(User $user = null)
    {
        if (is_null($user))
        {
            $user = $this->user;
        }

        if (! $this->isAuthenticated())
        {
            $this->actingAs($user);

            Session::push('user.company.id', $user->company_id);
        }

        return $user;
    }

    public function a_guest_can_not_access(array $actions) :void
    {
        $verbs = [
            'index' => 'get',
            'create' => 'get',
            'store' => 'post',
            'show' => 'get',
            'edit' => 'get',
            'update' => 'put',
            'destroy' => 'delete',
        ];

        foreach ($actions as $action => $parameters) {
            $this->assertAuthenticationRequired($action, $verbs[$action], $parameters);
        }
    }

    protected function assertAuthenticationRequired(string $action, string $method = 'get', array $parameters = [])
    {
        $this->$method(route($this->baseRouteName . '.' . $action, $parameters))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(basename(route('login')));

        $method .= 'Json';
        $this->$method(route($this->baseRouteName . '.' . $action, $parameters))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function a_user_can_not_see_things_from_a_different_company(array $parameters)
    {
        $this->signIn();

        $this->a_user_of_a_different_company_gets_a_404('show', 'get', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('edit', 'get', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('update', 'put', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', $parameters);
    }

    protected function a_user_of_a_different_company_gets_a_404(string $route, string $method = 'get', array $parameters = [])
    {
        $response = $this->$method(route($this->baseRouteName . '.' . $route, $parameters))
            ->assertStatus(Response::HTTP_NOT_FOUND, $route);
    }

    public function getIndexViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('index', $parameters);
    }

    public function getCreateViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('create', $parameters);
    }

    public function getShowViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('show', $parameters);
    }

    public function getEditViewResponse(array $parameters = []) : TestResponse
    {
        return $this->getViewResponse('edit', $parameters);
    }

    protected function getViewResponse(string $action, array $parameters = []) : TestResponse
    {
        $this->signIn();

        $response = $this->get(route($this->baseRouteName . '.' . $action, $parameters));
        $response->assertStatus(Response::HTTP_OK);

        return $response;
    }

    public function getCollection(array $parameters = [], int $assertJsonCount = 3) : TestResponse
    {
        $this->signIn();

        $response = $this->getJson(route($this->baseRouteName . '.index', $parameters))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($assertJsonCount);

        return $response;
    }

    public function getPaginatedCollection(array $parameters = [], int $assertJsonCount = 3)
    {
        $this->signIn();

        $response = $this->json('get', route($this->baseRouteName . '.index', $parameters), [
            'perPage' => 25,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data',
                'total',
            ])
            ->assertJsonCount($assertJsonCount, 'data');
    }

    public function deleteModel(Model $model, array $parameters) : TestResponse
    {
        $this->signIn();

        $table = $model->getTable();

        $this->assertDatabaseHas($table, [
            'id' => $model->id
        ]);

        $this->assertTrue($model->isDeletable());
        $response = $this->delete(route($this->baseRouteName . '.destroy', $parameters))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing($table, [
            'id' => $model->id
        ]);

        return $response;
    }
}
