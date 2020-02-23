<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyTransactions extends Mailable
{
    use Queueable, SerializesModels;

    public $transactions = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.company.transactions')
            ->subject('Neue Guthaben')
            ->from('noreply@rechnungspilot.de', config('app.name'));
    }
}
