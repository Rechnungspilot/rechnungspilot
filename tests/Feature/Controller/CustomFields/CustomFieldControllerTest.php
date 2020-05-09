<?php

namespace Tests\Feature\Controller\Customfields;

use App\Models\CustomFields\CustomField;
use App\Receipts\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CustomFieldControllerTest extends TestCase
{
    protected $baseRouteName = 'customfield';
    protected $baseViewPath = 'customfield';
    protected $className = CustomField::class;

    protected $types = [
        'belege',
        'kontakte',
    ];

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => ['type' => $this->types[0]],
            'store' => ['type' => $this->types[0]],
            'update' => ['customfield' => $id],
            'destroy' => ['customfield' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_models_of_an_other_company()
    {
        $modelOfADifferentCompany = factory($this->className)->create();

        $this->signIn();

        $this->a_user_of_a_different_company_gets_a_404('update', 'put', ['customfield' => $modelOfADifferentCompany->id]);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', ['customfield' => $modelOfADifferentCompany->id]);

        $response = $this->getJson(route($this->baseRouteName . '.index', ['type' => $this->types[0]]))
            ->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        foreach ($this->types as $type) {
            $this->getIndexViewResponse(['type' => $type]);
        }
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        factory($this->className, 3)->create([
            'company_id' => $this->user->company_id,
            'for' => $this->types[0],
        ]);

        $this->signIn();

        $this->getCollection(['type' => $this->types[0]]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $inputType = 'text';

        $this->signIn();

        foreach ($this->types as $type) {

            $name = ucfirst($type) . ' Feld';

            $postData = [
                'name' => $name,
                'input_type' => 'text',
            ];
            $response = $this->post(route($this->baseRouteName . '.store', ['type' => $type]), $postData)
                ->assertStatus(Response::HTTP_CREATED);

            $data = [
                'company_id' => $this->user->company_id,
                'for' => $type,
                'name' => $name,
                'default' => false,
                'input_type' => $inputType,
            ];

            $this->assertDatabaseHas('custom_fields', $data);

            $response = $this->json('POST', route($this->baseRouteName . '.store', ['type' => $type]), $postData);
            $response->assertStatus(Response::HTTP_CREATED)
                ->assertJsonStructure(['id', 'name'])
                ->assertJson($data);
        }
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
       $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['customfield' => $model->id]), [
            'name' => $model->name . ' updated',
            'input_type' => $model->input_type,
            'default' => true,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertSessionHasNoErrors()
            ->assertJson([
                'id' => $model->id,
                'name' => $model->name . ' updated',
                'input_type' => $model->input_type,
            ]);

        $this->assertDatabaseHas('custom_fields', [
            'id' => $model->id,
            'name' => $model->name . ' updated',
            'input_type' => $model->input_type,
            'default' => 1,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model_if_it_is_deletable()
    {
        $this->withoutExceptionHandling();

        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->deleteModel($model, ['customfield' => $model->id])
            ->assertRedirect(route($this->baseRouteName . '.index', ['type' => $model->for]));
    }
}
