<?php

namespace Tests\Feature\Controller\Times;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Order;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Completed;
use App\Time;
use App\Todos\Todo;
use App\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RecordingControllerTest extends TestCase
{
    protected $unit;
    protected $item;
    protected $todo;

    protected $baseRouteName = 'time.recording';
    protected $baseViewPath = 'time.recording';
    protected $className = Time::class;

    protected function setUp() : void
    {
        parent::setUp();

        $this->unit = factory(Unit::class)->create([
            'company_id' => $this->company->id,
        ]);
        $this->item = factory(Item::class)->create([
            'company_id' => $this->company->id,
            'unit_id' => $this->unit->id,
            'type' => Item::TYPE_SERVICE,
        ]);
        $this->todo = factory(Todo::class)->create([
            'company_id' => $this->company->id,
            'item_id' => $this->item->id,
            'creator_id' => $this->user->id,
        ]);
    }

    /**
     * @test
     */
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'destroy' => ['time' => $id],
        ];
        $this->a_guest_can_not_access($actions);
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
    public function a_user_can_start_recording()
    {
        $postData = [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'todo_id' => $this->todo->id,
        ];

        $this->signIn();

        $now = now();
        $response = $this->post(route($this->baseRouteName . '.store'), $postData);
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('times', [
            'id' => 1,
            'company_id' => $this->company->id,
            'start_at' => $now->format('Y-m-d H:i:s'),
            'end_at' => null,
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->json('POST', route($this->baseRouteName . '.store'), $postData);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'item', 'user', 'timeable'])
            ->assertJson([
                'id' => 2,
                'company_id' => $this->company->id,
                'start_at' => $now->format('Y-m-d H:i:s'),
                'user_id' => $this->user->id,
                'item_id' => $this->item->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_end_recording()
    {
        $time = $this->createTime();

        $this->signIn();

        $now = now();
        $response = $this->deleteJson(route($this->baseRouteName . '.destroy', ['time' => $time->id]), [
            'note' => 'Notiz',
            'completeOrder' => false,
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'item', 'user'])
            ->assertJson([
                'id' => $time->id,
                'start_at' => $time->start_at->format('Y-m-d H:i:s'),
                'user_id' => $this->user->id,
                'item_id' => $this->item->id,
            ]);;

        $this->assertDatabaseHas('times', [
            'id' => $time->id,
            'start_at' => $time->start_at->format('Y-m-d H:i:s'),
            'end_at' => $now->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'note' => 'Notiz',
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_end_recording_and_complete_order()
    {
        $time = $this->createTimeWithOrder();
        $order = $time->timeable->todoable;

        $this->signIn();

        $now = now();
        $response = $this->deleteJson(route($this->baseRouteName . '.destroy', ['time' => $time->id]), [
            'note' => 'Notiz',
            'completeOrder' => true,
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'item', 'user'])
            ->assertJson([
                'id' => $time->id,
                'start_at' => $time->start_at->format('Y-m-d H:i:s'),
                'user_id' => $this->user->id,
                'item_id' => $this->item->id,
            ]);;

        $this->assertDatabaseHas('times', [
            'id' => $time->id,
            'start_at' => $time->start_at,
            'end_at' => $now->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'note' => 'Notiz',
        ]);

        $this->assertEquals(Completed::class, $order->fresh()->latest_status_type);

        $this->assertDatabaseHas('receipt_statuses', [
            'receipt_id' => $order->id,
            'type' => Completed::class
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_end_recording_and_a_no_receipt_item_is_created_if_industryHours_are_zero()
    {
        $this->withoutExceptionHandling();

        $time = $this->createTimeWithOrder([
            'start_at' => now(),
        ]);
        $order = $time->timeable->todoable;

        $this->signIn();

        $this->assertCount(0, $order->fresh()->items);

        $now = now();
        $response = $this->deleteJson(route($this->baseRouteName . '.destroy', ['time' => $time->id]), [
            'note' => 'Notiz',
            'completeOrder' => false,
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'item', 'user'])
            ->assertJson([
                'id' => $time->id,
                'start_at' => $time->start_at->format('Y-m-d H:i:s'),
                'user_id' => $this->user->id,
                'item_id' => $this->item->id,
            ]);;

        $this->assertDatabaseHas('times', [
            'id' => $time->id,
            'start_at' => $time->start_at->format('Y-m-d H:i:s'),
            'end_at' => $now->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'note' => 'Notiz',
        ]);

        $this->assertCount(0, $order->fresh()->items);

        $this->assertDatabaseMissing('item_receipt', [
            'receipt_id' => $order->id,
            'item_id' => $this->item->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_end_recording_and_a_receipt_item_is_created()
    {
        $this->withoutExceptionHandling();

        $time = $this->createTimeWithOrder([
            'start_at' => now()->subHours(2),
        ]);
        $order = $time->timeable->todoable;

        $this->signIn();

        $this->assertCount(0, $order->fresh()->items);

        $now = now();
        $response = $this->deleteJson(route($this->baseRouteName . '.destroy', ['time' => $time->id]), [
            'note' => 'Notiz',
            'completeOrder' => false,
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'item', 'user'])
            ->assertJson([
                'id' => $time->id,
                'start_at' => $time->start_at->format('Y-m-d H:i:s'),
                'user_id' => $this->user->id,
                'item_id' => $this->item->id,
            ]);;

        $this->assertDatabaseHas('times', [
            'id' => $time->id,
            'start_at' => $time->start_at->format('Y-m-d H:i:s'),
            'end_at' => $now->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'note' => 'Notiz',
        ]);

        $this->assertCount(1, $order->fresh()->items);

        $this->assertDatabaseHas('item_receipt', [
            'receipt_id' => $order->id,
            'item_id' => $this->item->id,
            'quantity' => 2,
            'receiptable_type' => Time::class,
            'receiptable_id' => $time->id,
        ]);
    }

    protected function createTime(array $attributes = []) : Time
    {
        return factory($this->className)->create(array_merge([
            'company_id' => $this->company->id,
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'start_at' => now(),
            'end_at' => null,
        ], $attributes));
    }

    protected function createTimeWithOrder(array $attributes = []) : Time
    {
        $contact = factory(Contact::class)->create([
            'company_id' => $this->company->id,
        ]);

        $this->todo->todoable()->associate(factory(Order::class)->create([
            'company_id' => $this->company->id,
            'contact_id' => $contact->id,
        ]));
        $this->todo->save();

        $time = $this->createTime($attributes);
        $time->timeable()->associate($this->todo);
        $time->save();

        return $time;
    }
}
