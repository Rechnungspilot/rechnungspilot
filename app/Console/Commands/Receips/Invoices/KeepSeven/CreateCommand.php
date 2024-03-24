<?php

namespace App\Console\Commands\Receips\Invoices\KeepSeven;

use App\Jobs\CacheItem;
use App\Receipts\Invoice;
use App\Jobs\CacheContact;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CreateCommand extends Command
{
    protected $signature = 'receipts:invoices:keepseven:create
        {receipt : The receipt to create the invoice from.}
        {api-token : The API token to use for the request.}
        {--send : Send the invoice after creation.}';

    protected $description = 'Creates the invoice for Cardmonitor.';

    public function handle()
    {
        $last_month = now()->subMonth();
        $start_of_last_month = now()->subMonth()->startOfMonth();
        $end_of_last_month = now()->subMonth()->endOfMonth();

        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->argument('api-token')
            ])
            ->get('https://cardmonitor.d15r.de/api/user/revenue', [
                'from' => $start_of_last_month->format('Y-m-d'),
                'to' => $end_of_last_month->format('Y-m-d'),
            ])
            ->json();
        $revenue = $response['revenue'];

        $from = Invoice::findOrfail($this->argument('receipt'));
        $invoice = Invoice::from($from);
        $invoice->update([
            'date' => $end_of_last_month,
            'date_due' => $end_of_last_month,
        ]);

        $item = $invoice->items()->first();
        $item->update([
            'quantity' => 1,
            'unit_price' => $revenue * 0.025,
            'description' => 'Umsatz ' . $last_month->monthName . ': ' . number_format($revenue, 2, ',', '.') . ' €',
        ]);

        $invoice->refresh()->load('items');

        $invoice->cache();
        CacheContact::dispatch($invoice->contact);
        CacheItem::dispatch($item->item);

        $this->line('Invoice for ' . $last_month->monthName . '(' . $start_of_last_month->format('Y-m-d') . ' - ' . $end_of_last_month->format('Y-m-d') . ') created: ' . $invoice->name);
        $this->line('Invoice amount: ' . number_format($revenue, 2, ',', '.') . ' € -> '  . number_format($item->gross / 100, 2, ',', '.') . ' €');

        if ($this->option('send')) {
            $invoice->send();
            $this->line('Invoice sent' . $invoice->name);
        }

        return self::SUCCESS;
    }
}
