<?php

namespace Tests\Feature\Controller\Receipts\Abos;

use PHPUnit\Framework\Attributes\Test;
use App\Contacts\Contact;
use App\Receipts\Abos\Abo;
use App\Receipts\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActiveControllerTest extends TestCase
{
    protected $baseRouteName = 'receipt.abo.active';

    /**
     */
    #[Test]
    public function guest_can_not_access_the_following_routes()
    {
        $id = factory(Abo::class)->create()->id;

        $actions = [
            'store' => ['abo' => $id],
            'destroy' => ['abo' => $id],
        ];
        $this->a_guest_can_not_access($actions);
    }

    /**
     */
    #[Test]
    public function a_user_can_not_activate_an_abo_of_an_other_company()
    {
        $aboOfADifferentCompany = factory(Abo::class)->create();

        $this->signIn();

        $this->a_user_of_a_different_company_gets_a_404('store', 'post', ['abo' => $aboOfADifferentCompany->id]);

        $this->a_user_of_a_different_company_gets_a_404('destroy', 'delete', ['abo' => $aboOfADifferentCompany->id]);
    }

    /**
     */
    #[Test]
    public function a_user_can_activate_an_abo()
    {
        $this->signIn();

        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        Term::setup($this->user->company_id);

        $abo = factory(Abo::class)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
        ]);

        $response = $this->post(route($this->baseRouteName . '.store', ['abo' => $abo->id]));

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->id,
            'active' => 1,
        ]);
    }

    /**
     */
    #[Test]
    public function a_user_can_deactivate_an_abo()
    {
        $this->signIn();

        $this->contact = factory(Contact::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        Term::setup($this->user->company_id);

        $abo = factory(Abo::class)->create([
            'company_id' => $this->user->company_id,
            'contact_id' => $this->contact->id,
        ]);

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->id,
            'active' => 0,
        ]);

        $abo->settings()->update([
            'active' => true,
        ]);

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->id,
            'active' => 1,
        ]);

        $response = $this->delete(route($this->baseRouteName . '.destroy', ['abo' => $abo->id]));

        $this->assertDatabaseHas('abo_settings', [
            'id' => $abo->id,
            'active' => 0,
        ]);
    }
}
