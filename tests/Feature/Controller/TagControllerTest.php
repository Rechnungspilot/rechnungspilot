<?php

namespace Tests\Feature\Controller;

use App\Tag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    protected $user;

    protected $baseRouteName = 'tag';
    protected $type = 'test-type';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = $this->createTag()->id;

        $actions = [
            'index' => ['type' => $this->type],
            'store' => ['type' => $this->type],
            'update' => ['type' => $this->type, 'tag' => $id],
            'destroy' => ['type' => $this->type, 'tag' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_tags_of_an_other_company()
    {
        $tag = factory(Tag::class)->create([
            'type' => $this->type
        ]);

        $this->signIn($this->user);

        $response = $this->json('get', route($this->baseRouteName . '.index', ['type' => $this->type]))
            ->assertJsonCount(0);

        $this->a_user_of_a_different_company_gets_a_404('update', 'put', ['type' => $this->type, 'tag' => $tag->id]);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', ['type' => $this->type, 'tag' => $tag->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->signIn($this->user);

        $response = $this->get(route($this->baseRouteName . '.index', ['type' => $this->type]));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_tags()
    {
        $this->signIn($this->user);

        $tags = factory(Tag::class, 3)->create([
            'company_id' => $this->user->company_id,
            'type' => $this->type,
        ]);

        factory(Tag::class, 1)->create();

        $response = $this->json('get', route($this->baseRouteName . '.index', ['type' => $this->type]), [
            'searchtext' => '',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_tag()
    {
        $this->signIn($this->user);

        $name = 'Neuer Tag';

        $data = [
            'company_id' => $this->user->company_id,
            'type' => $this->type,
            'name' => $name,
            'slug' => Str::slug($name),
        ];

        $response = $this->json('post', route($this->baseRouteName . '.store', ['type' => $this->type]), [
            'name' => $name,
            'type' => $this->type,
        ])->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);

        $this->assertDatabaseHas('tags', $data);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_tag()
    {
        $tag = $this->createTag();

        $name = 'Updated';

        $data = [
            'company_id' => $this->user->company_id,
            'type' => $this->type,
            'name' => $name,
            'slug' => Str::slug($name),
        ];

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['tag' => $tag->id]), [
            'name' => $name,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tags', $data + [
            'id' => $tag->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_tag()
    {
        $this->signIn($this->user);

        $tag = $this->createTag();

        $response = $this->delete(route($this->baseRouteName . '.destroy', ['tag' => $tag->id]));

        $this->assertDatabaseMissing('tags', [
            'id', $tag->id
        ]);
    }

    protected function createTag(array $attributes = []) : Tag
    {
        return factory(Tag::class)->create(array_merge([
            'company_id' => $this->user->company_id,
            'type' => $this->type,
        ], $attributes));
    }
}
