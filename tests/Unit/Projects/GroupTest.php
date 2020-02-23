<?php

namespace Tests\Unit\Projects;

use App\Projects\Group;
use App\Projects\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_projects()
    {
        $projects_count = 5;
        $user = $this->signIn();
        $group = factory(Group::class)->create(['company_id' => $user->company_id]);
        $projects = factory(Project::class, $projects_count)->create([
            'company_id' => $group->company_id,
            'project_group_id' => $group->id,
            'creator_id' => $user->id,
        ]);

        $this->assertCount($projects_count, $group->projects);
    }
}
