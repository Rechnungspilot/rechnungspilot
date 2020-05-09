<?php

namespace Tests\Feature\Controller\Userfiles;

use App\Userfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserfileControllerTest extends TestCase
{
    protected $baseRouteName = 'userfile';
    protected $className = Userfile::class;

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'update' => ['userfile' => $id],
            'destroy' => ['userfile' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_contacts_of_an_other_company()
    {
        $userfileOfADifferentCompany = factory($this->className)->create();

        $parameters = ['userfile' => $userfileOfADifferentCompany->id];

        $this->signIn();

        $this->a_user_of_a_different_company_gets_a_404('update', 'put', $parameters);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', $parameters);

        $response = $this->json('get', route($this->baseRouteName . '.index'))
            ->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse();
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_items()
    {
        $models = factory($this->className, 3)->create([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_file()
    {
        Storage::fake(config('app.storage_disk_userfiles'));

        $this->signIn();

        $file = UploadedFile::fake()->image('file.jpg');

        $response = $this->post(route($this->baseRouteName . '.store'), [
            'files' => [ $file ],
        ]);
        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect();

        $this->assertDatabaseHas('userfiles', [
            'id' => 1,
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        $userfile = Userfile::first();

        Storage::disk(config('app.storage_disk_userfiles'))->assertExists($userfile->filename);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), ['files' => [ $file ]]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_userfile()
    {
        $this->signIn($this->user);

        $userfile = $this->createUserfile();

        $response = $this->put(route($this->baseRouteName . '.update', ['userfile' => $userfile->id]), [
            'name' => $userfile->name . 'updated',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertSessionHasNoErrors()
            ->assertJson($userfile->fresh()->load('fileable')->toArray());

        $this->assertDatabaseHas('userfiles', [
            'id' => $userfile->id,
            'name' => $userfile->name . 'updated',
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_an_item_if_it_is_deletable()
    {
        $this->withoutExceptionHandling();

        $this->signIn($this->user);

        $model = $this->createUserfile();

        $this->deleteModel($model, ['userfile' => $model->id])
            ->assertRedirect();

        Storage::disk(config('app.storage_disk_userfiles'))->assertMissing($model->filename);
    }

    protected function createUserfile() : Userfile
    {
        Storage::fake(config('app.storage_disk_userfiles'));
        $file = UploadedFile::fake()->image('file.jpg');
        $userfile = Userfile::fromUploadedFile($file);

        Storage::disk(config('app.storage_disk_userfiles'))->assertExists($userfile->filename);
        $this->assertDatabaseHas('userfiles', [
            'id' => $userfile->id,
        ]);

        return $userfile;
    }
}
