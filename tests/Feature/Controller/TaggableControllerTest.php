<?php

namespace Tests\Feature\Controller;

use App\Contacts\Contact;
use App\Item;
use App\Receipt\Letter;
use App\Receipts\Abos\Abo;
use App\Receipts\Delivery;
use App\Receipts\Dun;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Tag;
use App\Time;
use App\Todos\Todo;
use App\Transaction;
use App\User;
use App\Userfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaggableControllerTest extends TestCase
{
    protected $types = [
        // 'abos' => Abo::class,
        // 'angebote' => Quote::class,
        // 'artikel' => Item::class,
        // 'aufgaben' => Todo::class,
        // 'auftraege' => Order::class,
        // 'ausgaben' => Expense::class,
        // 'belege' => Receipt::class,
        // 'briefe' => Letter::class,
        // 'buchungen' => Transaction::class,
        // 'dateien' => Userfile::class,
        // 'einnahmen' => Income::class,
        // 'kontakte' => Contact::class,
        // 'lieferscheine' => Delivery::class,
        // 'mahnungen' => Dun::class,
        // 'rechnungen' => Invoice::class,
        'team' => User::class,
        // 'zeiterfassung' => Time::class,
    ];

    /**
     * @test
     */
    public function a_user_can_attach_a_tag()
    {
        $this->signIn($this->user);

        foreach ($this->types as $type => $className) {
            $model = factory($className)->create([
                'company_id' => $this->user->company_id,
            ]);

            $tag = factory(Tag::class)->create([
                'company_id' => $this->user->company_id,
                'type' => $type,
            ]);

            $this->assertCount(0, $model->fresh()->tags);

            $response = $this->post(route('taggable.store', [
                'type' => $type,
                'model' => $model->id,
                'tag' => $tag->id,
            ]));

            $this->assertCount(1, $model->fresh()->tags);
        }
    }

    /**
     * @test
     */
    public function a_user_can_detach_a_tag()
    {
        $this->signIn($this->user);

        foreach ($this->types as $type => $className) {
            $model = factory($className)->create([
                'company_id' => $this->user->company_id,
            ]);

            $tag = factory(Tag::class)->create([
                'company_id' => $this->user->company_id,
                'type' => $type,
            ]);

            $this->assertCount(0, $model->fresh()->tags);

            $model->tags()->attach($tag);

            $this->assertCount(1, $model->fresh()->tags);

            $response = $this->delete(route('taggable.destroy', [
                'type' => $type,
                'model' => $model->id,
                'tag' => $tag->id,
            ]));

            $this->assertCount(0, $model->fresh()->tags);
        }
    }
}
