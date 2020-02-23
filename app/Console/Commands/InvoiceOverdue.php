<?php

namespace App\Console\Commands;

use App\Receipts\Invoice;
use App\Receipts\Statuses\Overdue;
use App\Scopes\HasCompanyScope;
use Illuminate\Console\Command;

class InvoiceOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:overdue';

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
        $overdue = Invoice::withoutGlobalScope(HasCompanyScope::class)
            ->possibleOverdue()
            ->whereDate('date_due', '<', now())
            ->get();

        $status = new Overdue();
        $status->user_id = 0;
        $status->data = [];

        foreach ($overdue as $key => $invoice) {
            $status->company_id = $invoice->company_id;
            $invoice->setStatus($status);
        }
    }
}
