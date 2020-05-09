<?php

namespace Tests\Feature\Controller\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class SetPasswordControllerTest extends TestCase
{
    protected $baseRouteName = 'password';

    /**
     * @test
     */
    public function users_can_not_access_the_following_routes()
    {
        $this->signIn();

        $response = $this->get(route($this->baseRouteName . '.create', ['user' => $this->user->id]));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('home'));

        $password = Str::random(8);
        $response = $this->post(route($this->baseRouteName . '.store', ['user' => $this->user->id]), [
            'password' => $password,
            'password_confirmed' => $password,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('home'));
    }

    /**
     * @test
     */
    public function invited_guests_can_visit_the_create_view()
    {
        $user = factory(User::class)->create([
            'company_id' => $this->user->company_id,
            'password' => null,
        ]);

        $response = $this->get($user->createPasswordUrl)
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function invited_guests_can_set_their_password()
    {
        $user = factory(User::class)->create([
            'company_id' => $this->user->company_id,
            'password' => null,
        ]);

        $password = Str::random(8);
        $response = $this->post($user->storePasswordUrl, [
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::Check($password, $user->fresh()->password));
    }

    /**
     * @test
     */
    public function invited_guests_can_not_set_a_password_from_another_user()
    {
        $user = factory(User::class)->create([
            'company_id' => $this->user->company_id,
            'password' => null,
        ]);

        $password = Str::random(8);
        $response = $this->post(route($this->baseRouteName . '.store', ['user' => $this->user->id]), [
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
