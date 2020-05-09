<?php

namespace Tests\Traits;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Unit;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;

trait BaseReceiptTests
{
    protected $contact;
    protected $invoice;
    protected $item;
    protected $unit;

    protected function setUp() : void
    {
        parent::setUp();

        $this->unit = factory(Unit::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->item = factory(Item::class)->create([
            'company_id' => $this->user->company_id,
            'unit_price' => 1.23,
            'unit_id' => $this->unit->id,
        ]);
        Term::setup($this->user->company_id);
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->getClassName())->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => [$this->getBaseRouteParameter() => $id],
            'edit' => [$this->getBaseRouteParameter() => $id],
            'update' => [$this->getBaseRouteParameter() => $id],
            'destroy' => [$this->getBaseRouteParameter() => $id],
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

        $response = $this->json('get', route($this->getBaseRouteName() . '.index'))
            ->assertStatus(Response::HTTP_OK)
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
    public function a_user_can_get_a_paginated_collection_of_invoices()
    {
        $this->createReceipt();
        $this->createReceipt();
        $this->createReceipt();

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_receipt()
    {
        $this->signIn();

        $firstContact = Contact::first();

        $response = $this->post(route($this->getBaseRouteName() . '.store'))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->getBaseRouteName() . '.' . $this->getRedirectRouteAction(), [$this->getBaseRouteParameter() => 1]));

        $this->assertDatabaseHas('receipts', [
            'id' => 1,
            'number' => 1,
            'contact_id' => $firstContact->id,
        ]);

        $receipt = Receipt::first();

        $this->assertCount(1, $receipt->statuses);

        $this->assertDatabaseHas('receipt_statuses', [
            'receipt_id' => $receipt->id,
            'type' => Draft::class,
        ]);

        $response = $this->json('POST', route($this->getBaseRouteName() . '.store'), [])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'contact_id' => $firstContact->id,
                'number' => 2
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_receipt_for_a_contact()
    {
        $this->signIn();

        $contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->assertCount(0, Receipt::all());

        $response = $this->post(route($this->getBaseRouteName() . '.store'), ['contact_id' => $contact->id])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->getBaseRouteName() . '.' . $this->getRedirectRouteAction(), [$this->getBaseRouteParameter() => 1]));

        $this->assertDatabaseHas('receipts', [
            'id' => 1,
            'number' => 1,
            'contact_id' => $contact->id,
        ]);

        $response = $this->json('POST', route($this->getBaseRouteName() . '.store'), ['contact_id' => $contact->id])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'contact_id' => $contact->id,
                'number' => 2
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $invoice = $this->createReceipt();

        $response = $this->getEditViewResponse([$this->getBaseRouteParameter() => $invoice->id]);

        $this->assertEditViewHas($response);
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
        $receipt = $this->createReceipt();

        $this->signIn($this->user);

        $response = $this->put(route($this->getBaseRouteName() . '.update', [$this->getBaseRouteParameter() => $receipt->id]), [
            'address' => '',
            'contact_id' => $receipt->contact_id,
            'date' => $receipt->date->format('d.m.Y'),
            'date_due' => $receipt->date_due->format('d.m.Y'),
            'number' => '5',
            'term_id' => $receipt->term_id,
            'text_above' => '',
            'text_below' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('receipts', [
            'id' => $receipt->id,
            'address' => null,
            'contact_id' => $receipt->contact_id,
            'date' => $receipt->date->format('Y-m-d H:i:s'),
            'date_due' => $receipt->date_due->format('Y-m-d H:i:s'),
            'number' => 5,
            'term_id' => $receipt->term_id,
            'text_above' => null,
            'text_below' => null,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_receipt_if_it_is_deletable()
    {
        $this->withoutExceptionHandling();

        $model = $this->createReceipt();

        $this->deleteModel($model, [$this->getBaseRouteParameter() => $model->id])
            ->assertRedirect(route($this->getBaseRouteName() . '.index'));
    }

    protected function createReceipt() : Receipt
    {
        return factory($this->getClassName())->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
        ]);
    }

    protected function getBaseRouteName() : string
    {
        return $this->baseRouteName;
    }

    protected function getBaseRouteParameter() : string
    {
        return $this->baseRouteParameter;
    }

    protected function getClassName() : string
    {
        return $this->className;
    }

    protected function getRedirectRouteAction() : string
    {
        return $this->redirectRouteAction ?? 'edit';
    }
}

?>