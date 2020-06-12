<?php

namespace App\Console\Commands\Receips\Invoices;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendKeepsevenLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:mail:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a mail with link to create an invoice';

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
        Mail::send(new \App\Mail\Receipts\SendKeepsevenLink());
    }
}
