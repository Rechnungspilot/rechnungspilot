<?php

namespace Tests\Unit\Models\Contacts;

use App\Contacts\Contact;
use App\Receipts\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_email_receipt()
    {
        $contact = factory(Contact::class)->create();

        $contact->email_receipt = 1;
        $this->assertEquals(1, $contact->email_receipt);

        $contact->email_receipt = -1;
        $this->assertNull($contact->email_receipt);
    }

    /**
     * @test
     */
    public function it_checks_if_it_is_deletable()
    {
        $contact = factory(Contact::class)->create();
        $companyId = $contact->company_id;

        $this->assertTrue($contact->isDeletable());

        $invoice = factory(Invoice::class)->create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
        ]);

        $this->assertFalse($contact->isDeletable(), 'contact has invoices');

        $invoice->statuses()->delete();
        $invoice->delete();

        $this->assertTrue($contact->isDeletable());
    }
}
