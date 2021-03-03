<?php

namespace Tests\Feature\Api\Contacts;

use App\Contacts\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_the_models_for_a_company()
    {
        $this->signIn();

        $existing_count = Contact::where('company_id', $this->user->company_id)->count();

        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $response = $this->get(route('api.companies.contacts.index', [
            'company' => $this->user->company_id
        ]));

        $response->assertJsonCount($existing_count + 1, 'data');
    }

    /**
     * @test
     */
    public function it_can_create_a_model_for_a_company()
    {
        $this->signIn();

        $response = $this->post(route('api.companies.contacts.store', [
            'company' => $this->user->company_id
        ]), [
            'firstname' => 'Vorname',
            'lastname' => 'Nachname',
            'company' => 'Firma',
            'email' => 'email@firma.de',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_can_get_a_model_for_a_company()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $contact = $this->company->contacts()->first();

        $response = $this->get(route('api.companies.contacts.show', [
            'company' => $this->user->company_id,
            'contact' => $contact->id
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $contact->id,
            ]);
    }
}
