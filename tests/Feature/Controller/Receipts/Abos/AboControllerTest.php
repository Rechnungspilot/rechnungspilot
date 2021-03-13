<?php

namespace Tests\Feature\Controller\Receipts\Abos;

use App\Contacts\Contact;
use App\Receipts\Abos\Abo;
use App\Receipts\Abos\Settings;
use App\Receipts\Invoice;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Created;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\BaseReceiptTests;

class AboControllerTest extends TestCase
{
    use BaseReceiptTests;

    protected $baseRouteName = 'receipt.abo';
    protected $baseRouteParameter = 'abo';
    protected $className = Abo::class;
    protected $redirectRouteAction = 'show';

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->getClassName())->create()->id;

        $actions = [
            'index' => ['type' => 'rechnungen'],
            'store' => ['type' => 'rechnungen'],
            'show' => ['type' => 'rechnungen', $this->getBaseRouteParameter() => $id],
            'edit' => ['type' => 'rechnungen', $this->getBaseRouteParameter() => $id],
            'update' => ['type' => 'rechnungen', $this->getBaseRouteParameter() => $id],
            'destroy' => ['type' => 'rechnungen', $this->getBaseRouteParameter() => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_receipts_of_an_other_company()
    {
        $modelOfADifferentCompany = factory($this->getClassName())->create();

        $this->a_user_can_not_see_things_from_a_different_company([$this->getBaseRouteParameter() => $modelOfADifferentCompany->id]);

        $response = $this->json('get', route($this->getBaseRouteName() . '.index', [
            'type' => 'rechnungen'
        ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse([
            'type' => 'rechnungen'
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_invoices()
    {
        $receipt = $this->createReceipt();
        $this->createReceipt();
        $this->createReceipt();

        $this->getPaginatedCollection([
            'type' => 'invoice'
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_receipt()
    {
        $this->withoutExceptionHandling();

        $route = route($this->getBaseRouteName() . '.store', [
            'type' => 'invoice',
        ]);

        $this->signIn();

        $firstContact = Contact::first();
        $now = now()->startOfDay();

        $response = $this->post($route)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->getBaseRouteName() . '.' . $this->getRedirectRouteAction(), [
                $this->getBaseRouteParameter() => 1
            ]));

        $this->assertDatabaseHas('receipts', [
            'id' => 1,
            'number' => 1,
            'contact_id' => null,
        ]);

        $abo = Abo::first();

        $this->assertCount(1, $abo->statuses, 'status count');

        $this->assertDatabaseHas('receipt_statuses', [
            'receipt_id' => $abo->id,
            'type' => Created::class,
        ]);

        $this->assertCount(1, Settings::where('abo_id', $abo->id)->get(), 'settings count');

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->fresh()->settings->id,
            'abo_id' => $abo->id,
            'send_mail' => 0,
            'email' => null,
            'interval_value' => Abo::DEFAULT_INTERVAL['value'],
            'interval_unit' => Abo::DEFAULT_INTERVAL['unit'],
            'start_at' => $now->format('Y-m-d H:i:s'),
            'next_at' => $now->format('Y-m-d H:i:s'),
            'last_at' => null,
            'last_count' => 0,
            'last_type' => 0,
        ]);

        $this->assertCount(0, $abo->contacts, 'contacts count');

        $response = $this->json('POST', $route, [])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'number' => 2
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_receipt_for_a_contact()
    {
        $this->withoutExceptionHandling();

        $route = route($this->getBaseRouteName() . '.store', [
            'type' => 'rechnungen',
        ]);

        $this->signIn();

        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->assertCount(0, Receipt::all());

        $response = $this->post($route, ['contact_id' => $contact->id])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->getBaseRouteName() . '.' . $this->getRedirectRouteAction(), [$this->getBaseRouteParameter() => 1]));

        $this->assertDatabaseHas('receipts', [
            'id' => 1,
            'number' => 1,
            'contact_id' => null,
        ]);

        $abo = Abo::find(1);

        $this->assertCount(1, $abo->contacts, 'contacts count');

        $this->assertDatabaseHas('contact_receipt', [
            'receipt_id' => $abo->id,
            'contact_id' => $contact->id,
        ]);

        $response = $this->json('POST', $route, ['contact_id' => $contact->id])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'number' => 2
            ]);

    }

    protected function assertEditViewHas(TestResponse $response) : void
    {
        //
    }

    /**
     * @test
     */
    public function a_user_can_update_a_receipt()
    {
        $abo = $this->createReceipt();
        $now = now()->startOfDay();

        $route = route($this->getBaseRouteName() . '.update', [
            'type' => 'rechnungen',
            $this->getBaseRouteParameter() => $abo->id
        ]);

        $this->signIn($this->user);

        $response = $this->put($route, [
            'email' => '',
            'interval_unit' => 'days',
            'interval_value' => 1,
            'last_at' => '',
            'last_count' => 0,
            'last_type' => '0',
            'next_at' => $now->format('d.m.Y'),
            'number' => '5',
            'send_mail' => '0',
            'start_at' => $now->format('d.m.Y'),
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('receipts', [
            'id' => $abo->id,
            'date' => $abo->date->format('Y-m-d H:i:s'),
            'number' => 5,
        ]);

        $this->assertCount(1, Settings::where('abo_id', $abo->id)->get());

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->fresh()->settings->id,
            'abo_id' => $abo->id,
            'send_mail' => 0,
            'email' => null,
            'interval_value' => 1,
            'interval_unit' => 'days',
            'start_at' => $now->format('Y-m-d H:i:s'),
            'next_at' => $now->format('Y-m-d H:i:s'),
            'last_at' => null,
            'last_count' => 0,
            'last_type' => 0,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_receipt_if_it_is_deletable()
    {
        $this->withoutExceptionHandling();

        $model = $this->createReceipt();

        $this->deleteModel($model, [
            'type' => 'rechnungen',
            $this->getBaseRouteParameter() => $model->id
        ])
            ->assertRedirect(route($this->getBaseRouteName() . '.index', [
                'type' => 'rechnungen',
            ]));
    }

    protected function createReceipt() : Receipt
    {
        return Abo::create([
            'company_id' => $this->user->company_id,
            'settings_type' => Invoice::class,
            'contact_id' => $this->contact->id,
        ]);
    }
}
