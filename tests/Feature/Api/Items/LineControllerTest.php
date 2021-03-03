<?php

namespace Tests\Feature\Api\Items;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LineControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_a_model_for_a_company()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $model = $this->company->items()->first();

        $response = $this->get(route('api.companies.items.lines.index', [
            'company' => $this->user->company_id,
            'item' => $model->id
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $model->id,
            ]);
    }
}
