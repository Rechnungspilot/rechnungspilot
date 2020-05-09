<?php

namespace Tests\Feature\Controller\Receipts;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    protected $baseRouteName = 'receipt.item';

    protected $contact;
    protected $invoice;

    protected function setUp() : void
    {
        parent::setUp();

        $this->unit = factory(Unit::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->invoice = factory(Invoice::class)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
        ]);
        $this->item = factory(Item::class)->create([
            'company_id' => $this->user->company_id,
            'unit_price' => 1.23,
            'unit_id' => $this->unit->id,
        ]);
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = $this->createReceiptItem()->id;

        $actions = [
            'index' => ['receipt' => $this->invoice->id],
            'store' => ['receipt' => $this->invoice->id],
            'edit' => ['receiptItem' => $id],
            'update' => ['receipt' => $this->invoice->id, 'receiptItem' => $id],
            'destroy' => ['receipt' => $this->invoice->id, 'receiptItem' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_receipt_items_of_an_other_company()
    {
        $modelOfADifferentCompany = factory(ReceiptItem::class)->create();
        $modelOfADifferentCompany->load([
            'receipt',
        ]);

        $this->signIn();

        $this->a_user_of_a_different_company_gets_a_404('index', 'get', ['receipt' => $modelOfADifferentCompany->receipt->id]);
        $this->a_user_of_a_different_company_gets_a_404('store', 'post', ['receipt' => $modelOfADifferentCompany->receipt->id]);
        $this->a_user_of_a_different_company_gets_a_404('edit', 'get', ['receiptItem' => $modelOfADifferentCompany->id]);
        $this->a_user_of_a_different_company_gets_a_404('update', 'put', ['receipt' => $modelOfADifferentCompany->receipt->id, 'receiptItem' => $modelOfADifferentCompany->id]);
        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', ['receipt' => $modelOfADifferentCompany->receipt->id, 'receiptItem' => $modelOfADifferentCompany->id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_receipt_items()
    {
        $receiptItems = [];
        $receiptItems[] = $this->createReceiptItem();
        $receiptItems[] = $this->createReceiptItem();
        $receiptItems[] = $this->createReceiptItem();

        $this->getCollection(['receipt' => $this->invoice->id]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_receipt_item()
    {
        $this->signIn($this->user);

        $route = route($this->baseRouteName . '.store', ['receipt' => $this->invoice->id]);

        $this->assertCount(0, $this->invoice->items);

        $response = $this->post($route, ['item_id' => $this->item->id])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect();

        $this->assertDatabaseHas('item_receipt', [
            'company_id' => $this->user->company_id,
            'receipt_id' => $this->invoice->id,
            'item_id' => $this->item->id,
        ]);

        $this->assertCount(1, $this->invoice->fresh()->items);

        $response = $this->json('POST', $route, ['item_id' => $this->item->id])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                'receipt_id' => $this->invoice->id,
                'item_id' => $this->item->id,
            ]);

        $this->assertCount(2, $this->invoice->fresh()->items);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $receiptItem = $this->createReceiptItem();

        $this->getEditViewResponse(['receiptItem' => $receiptItem->id]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_contact()
    {
        $receiptItem = $this->createReceiptItem();
        $id = $receiptItem->id;

        $this->signIn($this->user);

        $response = $this->put(route($this->baseRouteName . '.update', ['receipt' => $this->invoice->id, 'receiptItem' => $id]), [
            'description' => '',
            'discount' => 10,
            'name' => $receiptItem->name . ' updated',
            'quantity' => 2.3,
            'tax' => 0.19,
            'unit_price' => 1.23,
            'unit_id' => $receiptItem->unit_id
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('item_receipt', [
            'description' => null,
            'discount' => 0.1,
            'name' => $receiptItem->name . ' updated',
            'quantity' => 2.3,
            'tax' => 0.19,
            'unit_price' => 1.23,
            'unit_id' => $receiptItem->unit_id
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_an_item_if_it_is_deletable()
    {
        $model = $this->createReceiptItem();

        $this->deleteModel($model, ['receipt' => $this->invoice->id, 'receiptItem' => $model->id])
            ->assertRedirect();
    }

    protected function createReceiptItem() : ReceiptItem
    {
        return $this->invoice->addItem($this->item);
    }

}
