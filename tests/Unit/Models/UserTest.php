<?php

namespace Tests\Unit\Models;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_path()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(route('team.show', ['team' => $user->id]), $user->path);
    }
}
