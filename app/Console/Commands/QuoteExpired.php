<?php

namespace App\Console\Commands;

use App\Receipts\Quote;
use App\Receipts\Statuses\Expired;
use App\Receipts\Statuses\Overdue;
use App\Scopes\HasCompanyScope;
use Illuminate\Console\Command;

class QuoteExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setzt den Status Überfällig bei entsprechenden Rechnungen';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $overdue = Quote::withoutGlobalScope(HasCompanyScope::class)
            ->possibleExpired()
            ->whereDate('date_due', '<', now())
            ->get();

        $status = new Expired();
        $status->user_id = 0;
        $status->data = [];

        foreach ($overdue as $key => $invoice) {
            $status->company_id = $invoice->company_id;
            $invoice->setStatus($status);
        }
    }
}
