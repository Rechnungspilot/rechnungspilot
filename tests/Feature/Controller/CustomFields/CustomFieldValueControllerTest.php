<?php

namespace Tests\Feature\Controller\CutomFields;

use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\Models\CustomFields\CustomFieldValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CustomFieldValueControllerTest extends TestCase
{
    protected $baseRouteName = 'customfieldvalue';
    protected $baseViewPath = 'customfieldvalue';
    protected $className = CustomFieldValue::class;
    protected $type = 'kontakte';


    protected function setUp() : void
    {
        parent::setUp();

        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        foreach ($this->customfields as $key => $customfield) {
            $this->contact->customfields()->create([
                'company_id' => $this->user->company_id,
                'custom_field_id' => $customfield->id,
            ]);
        }

        $this->signIn();

        $this->getCollection(['type' => $this->type, 'model' => $this->contact->id]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $value = ucfirst($this->type) . ' Feld';

        $postData = [
            'custom_field_ids' => [$this->customfields[0]->id],
        ];
        $response = $this->post(route($this->baseRouteName . '.store', ['type' => $this->type, 'model' => $this->contact->id]), $postData)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect();

        $data = [
            'company_id' => $this->user->company_id,
            'custom_field_id' => $this->customfields[0]->id,
            'value' => null,
        ];

        $this->assertDatabaseHas('custom_field_values', $data);

        $response = $this->json('POST', route($this->baseRouteName . '.store', ['type' => $this->type, 'model' => $this->contact->id]), $postData);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'value'])
            ->assertJson($data);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $model = factory($this->className)->create([
            'company_id' => $this->user->company_id,
            'custom_field_id' => $this->customfields[0]->id,
        ]);
        $model->load('customfield');

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['customfieldvalue' => $model->id]), [
            'value' => $model->value . 'updated',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertSessionHasNoErrors()
            ->assertJson([
                'id' => $model->id,
                'value' => $model->value . 'updated',
            ]);

        $this->assertDatabaseHas('custom_field_values', [
            'id' => $model->id,
            'value' => $model->value . 'updated',
        ]);

        $response = $this->put(route($this->baseRouteName . '.update', ['customfieldvalue' => $model->id]), [
            'value' => '',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertSessionHasNoErrors()
            ->assertJson([
                'id' => $model->id,
                'value' => null,
            ]);

        $this->assertDatabaseHas('custom_field_values', [
            'id' => $model->id,
            'value' => null,
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
            'custom_field_id' => $this->customfields[0]->id,
        ]);

        $this->deleteModel($model, ['customfieldvalue' => $model->id])
            ->assertRedirect();
    }
}
