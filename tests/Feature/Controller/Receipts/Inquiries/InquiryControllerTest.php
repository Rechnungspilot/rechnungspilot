<?php

namespace Tests\Feature\Controller\Receipts\Inquiries;

use App\Receipts\Inquiries\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\BaseReceiptTests;

class InquiryControllerTest extends TestCase
{
    use BaseReceiptTests;

    protected $baseRouteName = 'receipt.inquiry';
    protected $baseRouteParameter = 'inquiry';
    protected $className = Inquiry::class;

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createReceipt();

        $response = $this->getEditViewResponse([$this->getBaseRouteParameter() => $model->id]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_receipt()
    {
        $this->withoutExceptionHandling();

        $receipt = $this->createReceipt();
        $name = 'Neuer Name';

        $this->signIn($this->user);

        $response = $this->put(route($this->getBaseRouteName() . '.update', [$this->getBaseRouteParameter() => $receipt->id]), [
            'contact_id' => $receipt->contact_id,
            'date' => $receipt->date->format('d.m.Y'),
            'name' => $name,
            'text' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('receipts', [
            'id' => $receipt->id,
            'address' => null,
            'contact_id' => $receipt->contact_id,
            'date' => $receipt->date->format('Y-m-d H:i:s'),
            'name' => $name,
            'term_id' => null,
            'text' => null,
            'text_below' => null,
        ]);
    }
}
