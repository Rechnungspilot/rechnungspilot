<?php

namespace Tests\Feature\Controller;

use App\Company;
use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Unit;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    protected $user;
    protected $unit;

    protected $baseRouteName = 'artikel';

    protected function setUp() : void
    {
        parent::setUp();

        factory(Unit::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->unit = Unit::where('company_id', $this->user->company_id)->first();
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = $this->createItem()->id;

        $actions = [
            'index' => [],
            'create' => [],
            'store' => [],
            'show' => ['artikel' => $id],
            'edit' => ['artikel' => $id],
            'update' => ['artikel' => $id],
            'destroy' => ['artikel' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->signIn($this->user);

        $response = $this->get(route($this->baseRouteName . '.index'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_items()
    {
        $existing_count = Item::where('company_id', $this->user->company_id)->count();
        $count = 3;

        $this->signIn($this->user);

        $products = [];
        $products[] = $this->createItem();
        $products[] = $this->createItem();
        $products[] = $this->createItem();

        $response = $this->json('get', route($this->baseRouteName . '.index', [
            'perPage' => 25,
            'page' => 1,
            'type' => -1,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data',
                'total',
            ])
            ->assertJsonCount($existing_count + $count, 'data');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_create_view()
    {
        $this->signIn($this->user);

        $response = $this->get(route($this->baseRouteName . '.create'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function a_user_can_create_an_item()
    {
        $this->signIn($this->user);

        $name = 'Neuer Artikel';

        $this->assertDatabaseMissing('items', [
            'name' => $name,
        ]);

        $response = $this->post(route($this->baseRouteName . '.store'), [
            'name' => $name,
        ]);

        $this->assertDatabaseHas('items', [
            'name' => $name,
        ]);

        $item = Item::where('name', $name)->first();

        $response->assertStatus(302)
            ->assertRedirect(route($this->baseRouteName . '.show', ['artikel' => $item->id]));

        $this->assertDatabaseHas('item_price', [
            'company_id' => 1,
            'item_id' => $item->id,
            'start_at' => '1970-01-01 00:00:00',
            'unit_cost' => '0',
            'unit_price' => '0',
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), []);
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'unit_id', 'unit', 'tags'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'name' => 'Neuer Artikel',
                'unit_id' => $this->unit->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->signIn($this->user);

        $item = $this->createItem();

        $response = $this->get(route($this->baseRouteName . '.show', ['artikel' => $item->id]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $this->signIn($this->user);

        $item = $this->createItem();

        $response = $this->get(route($this->baseRouteName . '.edit', ['artikel' => $item->id]));

        $response->assertStatus(200);
    }


    /**
     * @test
     */
    public function a_user_can_not_see_the_item_show_view_from_a_different_company()
    {
        $differentCompany = factory(Company::class)->create();
        $differentUnit = factory(Unit::class)->create([
            'company_id' => $differentCompany->id
        ]);
        $item = $this->createItem([
            'company_id' => $differentCompany->id,
            'unit_id' => $differentUnit->id,
        ]);

        $this->signIn($this->user);

        $response = $this->get(route($this->baseRouteName . '.show', ['artikel' => $item->id]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function a_user_can_update_an_item()
    {
        $item = $this->createItem();

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['artikel' => $item->id]), [
            'cost_center' => '1',
            'decimals' => 2,
            'description' => '',
            'duration_hour' => 1,
            'duration_minute' => 30,
            'name' => $item->name . ' updated',
            'number' => '',
            'expense_account_number' => '1',
            'revenue_account_number' => '1',
            'tax' => 0.07,
            'type' => '1',
            'unit_cost' => '1,23',
            'unit_id' => $item->unit_id,
            'unit_price' => '0,12',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect($item->path);

        $this->assertDatabaseHas('items', [
            'cost_center' => '1',
            'description' => null,
            'duration' => 5400,
            'id' => $item->id,
            'name' => $item->name . ' updated',
            'number' => null,
            'revenue_account_number' => '1',
            'tax' => 0.07,
            'type' => '1',
            'unit_cost' => '1.23',
            'unit_id' => $item->unit_id,
            'unit_price' => '0.12',
        ]);

        $this->assertDatabaseHas('item_price', [
            'company_id' => $item->company_id,
            'item_id' => $item->id,
            'start_at' => today(),
            'unit_cost' => '1.23',
            'unit_price' => '0.12',
        ]);
    }

    /**
     * @test
     */
    public function a_name_is_required()
    {
        $item = $this->createItem();

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['artikel' => $item->id]), [
            'name' => '',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors('name');
    }

    /**
     * @test
     */
    public function a_user_can_delete_an_item_if_it_is_deletable()
    {
        $item = $this->createItem();
        $companyId = $item->company_id;
        $contact = factory(Contact::class)->create([
            'company_id' => $companyId,
        ]);
        $invoice = factory(Invoice::class)->create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
        ]);

        $this->signIn($this->user);

        $this->assertTrue($item->isDeletable());

        $invoice->addItem($item);
        $this->assertCount(1, $item->receipts, 'item has 1 receipt');

        $response = $this->delete(route($this->baseRouteName . '.destroy', ['artikel' => $item->id]));

        $response->assertStatus(302)
            ->assertRedirect(route($this->baseRouteName . '.index'));

        $this->assertDatabaseMissing('items', [
            'id', $item->id
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_an_item_json()
    {
        $item = $this->createItem();

        $this->signIn($this->user);

        $response = $this->json('delete', route($this->baseRouteName . '.destroy', ['artikel' => $item->id]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('items', [
            'id', $item->id,
        ]);
    }

    protected function createItem(array $attributes = []) : Item
    {
        return factory(Item::class)->create(array_merge([
            'company_id' => $this->user->company_id,
            'unit_id' => $this->unit->id,
        ], $attributes));
    }
}
