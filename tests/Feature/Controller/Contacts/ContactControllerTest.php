<?php

namespace Tests\Feature\Controller\Contacts;

use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    protected $baseRouteName = 'contacts';

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory(Contact::class)->create()->id;

        $actions = [
            'index' => [],
            'create' => [],
            'store' => [],
            'show' => ['contact' => $id],
            'edit' => ['contact' => $id],
            'update' => ['contact' => $id],
            'destroy' => ['contact' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_contacts_of_an_other_company()
    {
        $existing_count = Contact::where('company_id', $this->user->company_id)->count();

        $contactOfADifferentCompany = factory(Contact::class)->create();

        $this->a_user_can_not_see_things_from_a_different_company(['contact' => $contactOfADifferentCompany->id]);

        $response = $this->json('get', route($this->baseRouteName . '.index'))
            ->assertJsonCount($existing_count, 'data');
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
        $existing_count = Contact::where('company_id', $this->user->company_id)->count();
        $count = 3;

        $contacts = factory(Contact::class, $count)->create([
            'company_id' => $this->user->company_id
        ]);

        $this->getPaginatedCollection([], ($existing_count + $count));
    }

    /**
     * @test
     */
    public function a_user_can_see_the_create_view()
    {
        $this->getCreateViewResponse();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_contact()
    {
        $this->signIn();

        $response = $this->post(route($this->baseRouteName . '.store'));

        $contact = Contact::where('company_id', $this->user->company_id)->orderBy('id', 'DESC')->first();
        $existing_count = Contact::where('company_id', $this->user->company_id)->count();

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->baseRouteName . '.edit', ['contact' => $contact->id]));

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'firstname' => 'Neuer',
            'lastname' => 'Kunde',
            'number' => $existing_count,
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), []);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'name' => 'Kunde, Neuer',
                'number' => ($existing_count + 1)
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_contact_with_customfields()
    {
        $defaultCustomfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => true,
            'for' => CustomField::getForFromModel(new Contact()),
        ]);

        $defaultValue = 'test';
        $defaultCustomfieldWithDefaultValue = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => true,
            'for' => CustomField::getForFromModel(new Contact()),
            'default_value' => $defaultValue,
        ]);

        $defaultCustomfieldDifferentCompany = factory(CustomField::class)->create([
            'default' => true,
            'for' => CustomField::getForFromModel(new Contact()),
        ]);

        $customfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => false,
            'for' => CustomField::getForFromModel(new Contact()),
        ]);

        $this->signIn();

        $response = $this->post(route($this->baseRouteName . '.store'));

        $contact = Contact::where('company_id', $this->user->company_id)->orderBy('id', 'DESC')->first();
        $existing_count = Contact::where('company_id', $this->user->company_id)->count();

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->baseRouteName . '.edit', ['contact' => $contact->id]));

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'firstname' => 'Neuer',
            'lastname' => 'Kunde',
            'number' => $existing_count,
        ]);

        $this->assertDatabaseHas('custom_field_values', [
            'company_id' => $this->user->company_id,
            'custom_field_id' => $defaultCustomfield->id,
            'customfieldable_id' => $contact->id,
            'customfieldable_type' => Contact::class,
            'value' => null,
        ]);

        $this->assertDatabaseHas('custom_field_values', [
            'company_id' => $this->user->company_id,
            'custom_field_id' => $defaultCustomfieldWithDefaultValue->id,
            'customfieldable_id' => $contact->id,
            'customfieldable_type' => Contact::class,
            'value' => $defaultValue,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->getShowViewResponse(['contact' => $contact->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->getEditViewResponse(['contact' => $contact->id]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_contact()
    {
        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['contact' => $contact->id]), [
            'address' => '',
            'bankname' => '',
            'bic' => '',
            'city' => '',
            'company' => $contact->name . ' updated',
            'company_number' => '',
            'country' => '',
            'creditor_account_number' => '',
            'debitor_account_number' => '',
            'email' => '',
            'email_receipt' => -1,
            'euvatnumber' => '',
            'expense_term_id' => 0,
            'faxnumber' => '',
            'firstname' => '',
            'iban' => '',
            'invoice_term_id' => 0,
            'lastname' => '',
            'mobilenumber' => '',
            'number' => '' . $contact->number,
            'phonenumber' => '',
            'postcode' => '',
            'vatnumber' => '',
            'website' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect($contact->path);

        $this->assertDatabaseHas('contacts', [
            'address' => null,
            'bankname' => null,
            'bic' => null,
            'city' => null,
            'company' => $contact->name . ' updated',
            'company_id' => $this->user->company_id,
            'company_number' => null,
            'country' => null,
            'creditor_account_number' => null,
            'debitor_account_number' => null,
            'email' => null,
            'email_receipt' => null,
            'euvatnumber' => null,
            'expense_term_id' => '0',
            'faxnumber' => null,
            'firstname' => null,
            'iban' => null,
            'id' => $contact->id,
            'invoice_term_id' => '0',
            'lastname' => null,
            'mobilenumber' => null,
            'number' => $contact->number,
            'phonenumber' => null,
            'postcode' => null,
            'vatnumber' => null,
            'website' => null,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_contact_with_customfields()
    {
        $this->withoutExceptionHandling();

        $defaultCustomfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => true,
            'for' => CustomField::getForFromModel(new Contact()),
        ]);

        $defaultValue = 'test';
        $defaultCustomfieldWithDefaultValue = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => true,
            'for' => CustomField::getForFromModel(new Contact()),
            'default_value' => $defaultValue,
        ]);

        $customfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => false,
            'for' => CustomField::getForFromModel(new Contact()),
        ]);

        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $attachedCustomFiledValue = $contact->attachCustomfield($customfield, 'attached value');

        $this->assertCount(3, $contact->fresh()->customfields);

        $this->signIn();

        $data = [
            'address' => '',
            'bankname' => '',
            'bic' => '',
            'city' => '',
            'company' => $contact->name . ' updated',
            'company_number' => '',
            'country' => '',
            'creditor_account_number' => '',
            'debitor_account_number' => '',
            'email' => '',
            'email_receipt' => -1,
            'euvatnumber' => '',
            'expense_term_id' => 0,
            'faxnumber' => '',
            'firstname' => '',
            'iban' => '',
            'invoice_term_id' => 0,
            'lastname' => '',
            'mobilenumber' => '',
            'number' => '' . $contact->number,
            'phonenumber' => '',
            'postcode' => '',
            'vatnumber' => '',
            'website' => '',
        ];

        foreach ($contact->customfields as $customfieldvalue) {
            $data[$customfieldvalue->key] = $customfieldvalue->value . ' updated';
        }

        $response = $this->put(route($this->baseRouteName . '.update', ['contact' => $contact->id]), $data);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect($contact->path);

        foreach ($contact->customfields as $customfield) {
            $this->assertDatabaseHas('custom_field_values', [
                'id' => $customfield->id,
                'value' => trim($customfield->value . ' updated'),
            ]);
        }

    }

    /**
     * @test
     */
    public function a_user_can_delete_an_item_if_it_is_deletable()
    {
        $model = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->deleteModel($model, ['contact' => $model->id])
            ->assertRedirect(route($this->baseRouteName . '.index'));
    }
}
