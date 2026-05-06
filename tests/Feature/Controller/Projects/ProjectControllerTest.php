<?php

namespace Tests\Feature\Controller\Projects;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     *
     */
    #[Test]
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_stores_a_project()
    {

    }
}
