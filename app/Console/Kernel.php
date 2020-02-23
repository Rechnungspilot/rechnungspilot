<?php

namespace App\Console;

use App\Console\Commands\InvoiceOverdue;
use App\Console\Commands\QuoteExpired;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Overdue;
use App\Receipts\Statuses\Payment;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Viewed;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('company:createTransactions')->daily();
        $schedule->command('company:charge')->daily();
        $schedule->command('telescope:prune --hours=48')->daily();
        $schedule->command(InvoiceOverdue::class)->daily();
        $schedule->command(QuoteExpired::class)->daily();
        $schedule->command('abo:createInvoices')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
