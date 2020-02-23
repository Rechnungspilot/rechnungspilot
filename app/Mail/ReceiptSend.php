<?php

namespace App\Mail;

use App\Company;
use App\Receipts\Receipt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptSend extends Mailable
{
    use SerializesModels;

    public $receipt;
    public $company;
    public $text;

    protected $userfiles;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt, string $text, Collection $userfiles)
    {
        $this->receipt = $receipt;
        $this->text = $text;
        $this->userfiles = $userfiles;
        $this->receipt->load([
            'contact',
        ]);

        $this->company = Company::findOrFail($this->receipt->company_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        foreach ($this->userfiles as $userfile)
        {
            $this->attachFromStorageDisk(config('app.storage_disk_userfiles'), $userfile->filename, $userfile->name . '.' . $userfile->extension);
        }

        return $this->markdown('emails.receipts.send')
            ->with('receipt', $this->receipt)
            ->with('company', $this->company)
            ->with('text', $this->text)
            ->subject($this->receipt->typeName . ' ' . $this->receipt->name . ' von ' . $this->company->name)
            ->from('noreply@rechnungspilot.de', $this->company->name)
            ->replyTo($this->company->email ?: 'noreply@rechnungspilot.de', $this->company->name)
            ->attachData($this->receipt->pdf()->output(), $this->receipt->filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
