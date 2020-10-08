<?php

namespace Tests\Feature\Controller\Receipts\Abos;

use App\Contacts\Contact;
use App\Receipts\Abos\Abo;
use App\Receipts\Abos\Settings;
use App\Receipts\Statuses\Created;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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
    public function a_user_can_create_a_receipt()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $firstContact = Contact::first();
        $now = now()->startOfDay();

        $response = $this->post(route($this->getBaseRouteName() . '.store'))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route($this->getBaseRouteName() . '.' . $this->getRedirectRouteAction(), [$this->getBaseRouteParameter() => 1]));

        $this->assertDatabaseHas('receipts', [
            'id' => 1,
            'number' => 1,
            // 'contact_id' => $firstContact->id,
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

        // TODO: Abo für Kunde anlegen
        // $this->assertDatabaseHas('contact_receipt', [
        //     'receipt_id' => $abo->id,
        //     'contact_id' => $firstContact->id,
        // ]);

        $response = $this->json('POST', route($this->getBaseRouteName() . '.store'), [])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'name'])
            ->assertJson([
                'company_id' => $this->user->company_id,
                // 'contact_id' => $firstContact->id,
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

        $this->signIn($this->user);

        $response = $this->put(route($this->getBaseRouteName() . '.update', [$this->getBaseRouteParameter() => $abo->id]), [
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
}
