<?php

namespace App\Jobs\Imports;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Send;
use App\Receipts\Term;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Philip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $item;

    protected $notFoundContacts = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->item = $this->getItem();

        $file = Storage::get($this->path);
        $rows = explode("\n", $file);
        $contacts_count = 0;

        foreach ($rows as $row) {
            $data = explode(';', $row);
            if ($data[0] != 'H2 - DATEN') {
                continue;
            }

            $contacts_count++;

            $invoice = $this->row($data);
            if (is_null($invoice)) {
                continue;
            }

            $invoices[] = $invoice;
        }

        Storage::delete($this->path);

        return [
            'contacts_count' => $contacts_count,
            'not_found_contacts' => $this->notFoundContacts,
            'invoices_count' => count($invoices),
            'invoices' => $invoices,
        ];
    }

    protected function getItem() : Item {
        return Item::where('name', 'Wärmemenge')->first();
    }

    protected function row(array $data)
    {
        // $name = $data[6];
        $seriennummer = $data[14];
        $quantity = (float) $data[9];
        $contact = $this->getContact($seriennummer);
        if (is_null($contact)) {
            $this->notFoundContacts[] = $data;
            return null;
        }
        return $this->createInvoice($contact, $quantity);
    }

    protected function getContact(string $seriennummer)
    {
        return Contact::whereHas('customfields', function (Builder $query) use ($seriennummer) {
            $query->where('value', $seriennummer)
                ->whereHas('customfield', function (Builder $query) {
                    $query->where('name', 'Seriennummer');
                });
        })->first();
    }

    protected function createInvoice(Contact $contact, float $quantity) : Invoice
    {
        $term = Term::default(Invoice::class, $contact->invoice_term_id);

        $invoice = Invoice::create([
            'address' => $contact->billing_address,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        $invoice->addItem($this->item, ['quantity' => $quantity]);

        $partials = Invoice::where('contact_id', $contact->id)
            ->where('is_partial', true)
            ->whereNull('final_invoice_id')
            ->whereHas('statuses', function (Builder $query) {
                $query->where('type', Send::class);
            })->update([
            'final_invoice_id' => $invoice->id
        ]);

        $invoice->load(['items'])->cache();
        $invoice->contact = $contact;

        return $invoice;
    }
}
