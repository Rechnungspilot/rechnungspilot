<?php

namespace Tests\Feature\Controller\Projects;

use App\Projects\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $jsonStructure = [
        'name',
        'company_id',
    ];

    /**
     * @test
     */
    public function it_stores_a_project_group()
    {
        $user = $this->signIn();
        $data = [
            'name' => 'Neue Gruppe',
            'company_id' => $user->company_id,
        ];

        $response = $this->post(route('projectgroup.store'), []);

        $response->assertStatus(201)
            ->assertJsonStructure($this->jsonStructure)
            ->assertJson($data);

        $this->assertDatabaseHas('project_groups', $data);
    }

    /**
     * @test
     */
    public function it_updates_a_project_group()
    {
        $user = $this->signIn();
        $group = factory(Group::class)->create([
            'company_id' => $user->company_id,
        ]);
        $this->assertDatabaseHas('project_groups', $group->getOriginal());

        $updatedName = 'updated name';
        $response = $this->put(route('projectgroup.update', ['group' => $group->id]), [
            'name' => $updatedName,
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('project_groups', [
            'id' => $group->id,
            'name' => $updatedName,
        ]);

    }

    /**
     * @test
     */
    public function it_deletes_a_group_with_its_dependencies()
    {

    }
}
