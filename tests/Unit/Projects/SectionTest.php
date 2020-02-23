<?php

namespace Tests\Unit\Projects;

use App\Projects\Section;
use App\Todos\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_todos()
    {
        $section = factory(Section::class)->create();
        $project = $section->project;
        $user = $project->creator;

        $this->assertCount(0, $section->todos);

        $section->todos()->save(factory(Todo::class)->make([
            'company_id' => $user->company_id,
            'creator_id' => $user->id,
        ]));

        $this->assertCount(1, $section->fresh()->todos);

    }
}
