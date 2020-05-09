<?php

namespace Tests\Feature\Controller\Users;

use App\Mail\TeamInvite;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteControllerTest extends TestCase
{
    protected $baseRouteName = 'team.invite';

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $user = factory(User::class)->create();

        $actions = [
            'store' => ['user' => $user->id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $this->signIn();

        $user = factory(User::class)->create([
            'company_id' => $this->user->company_id,
            'password' => null,
        ]);

        $response = $this->post(route($this->baseRouteName . '.store', ['user' => $user->id]), [
            'email' => $user->email,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        Mail::assertSent(TeamInvite::class);

        // $this->assertDatabaseHas('invitatons', [
        //     'user_id' => $user->id,
        //     'accepted_at' => null,
        // ]);
    }

    /**
     * @test
     */
    public function a_user_can_revoke_an_invitation()
    {
        // Ist die Tabelle "invitations" wirklich notwendig?
        // User können gelöscht werden
        // Alternative uuid ändern??

        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
