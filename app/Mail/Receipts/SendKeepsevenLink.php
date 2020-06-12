<?php

namespace App\Mail\Receipts;

use App\Company;
use App\Contacts\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendKeepsevenLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $contact = Contact::find(42);
        $company = Company::find(1);

        return $this->to($contact->email)
            ->from($company->email, $company->name)
            ->subject('Link zum Erstellen der Rechnung')
            ->markdown('emails.receipts.send_keepseven_link')
            ->with('url', URL::temporarySignedRoute('receipt.invoice.keepseven.create', now()->addHours(24)));
    }
}
