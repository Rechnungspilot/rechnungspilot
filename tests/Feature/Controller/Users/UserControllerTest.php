<?php

namespace Tests\Feature\Controller\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected $baseRouteName = 'team';

    protected function setUp() : void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $user = factory(User::class)->create();

        $actions = [
            'index' => [],
            'create' => [],
            'store' => [],
            'show' => ['team' => $user->id],
            'edit' => ['team' => $user->id],
            'update' => ['team' => $user->id],
            'destroy' => ['team' => $user->id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_users_of_an_other_company()
    {
        $userOfADifferentCompany = factory(User::class)->create();

        $this->a_user_can_not_see_things_from_a_different_company(['team' => $userOfADifferentCompany->id]);

        $response = $this->json('get', route($this->baseRouteName . '.index'))
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse();
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_users()
    {
        $users = factory(User::class, 3)->create([
            'company_id' => $this->user->company_id
        ]);

        $this->getPaginatedCollection([], 4);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_user()
    {
        $this->signIn($this->user);

        $response = $this->post(route($this->baseRouteName . '.store'));
        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->baseRouteName . '.show', ['team' => 2]));

        $this->assertDatabaseHas('users', [
            'id' => 2,
            'company_id' => $this->user->company_id,
            'firstname' => 'Neuer',
            'lastname' => 'Mitarbeiter',
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), []);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'id' => 3,
                'company_id' => $this->user->company_id,
                'name' => 'Mitarbeiter, Neuer',
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->getShowViewResponse(['team' => $this->user->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $this->getEditViewResponse(['team' => $this->user->id]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_user()
    {
        $this->signIn();

        $response = $this->put(route($this->baseRouteName . '.update', ['team' => $this->user->id]), [
            'address' => '',
            'bankname' => '',
            'bic' => '',
            'city' => '',
            'email' => $this->user->email,
            'firstname' => '',
            'hex_color_code' => $this->user->hex_color_code,
            'iban' => '',
            'lastname' => '',
            'mobilenumber' => '',
            'number' => '',
            'phonenumber' => '',
            'postcode' => '',
            'roles' => $this->user->roles->pluck('id')->toArray(),
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'address' => null,
            'bankname' => null,
            'bic' => null,
            'city' => null,
            'email' => $this->user->email,
            'firstname' => null,
            'hex_color_code' => $this->user->hex_color_code,
            'iban' => null,
            'id' => $this->user->id,
            'lastname' => null,
            'mobilenumber' => null,
            'number' => null,
            'phonenumber' => null,
            'postcode' => null,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_user_if_it_is_deletable()
    {
        $model = factory(User::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->deleteModel($model, ['team' => $model->id])
            ->assertRedirect(route($this->baseRouteName . '.index'));
    }
}
