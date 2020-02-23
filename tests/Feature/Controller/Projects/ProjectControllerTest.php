<?php

namespace Tests\Feature\Controller\Projects;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     *
     * @test
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /* @test */
    public function it_stores_a_project()
    {

    }
}
