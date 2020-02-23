<?php

namespace App\Console\Commands;

use App\Banks\Account;
use App\Company;
use App\Mail\CompanyTransactions;
use App\Transaction;
use Carbon\Carbon;
use Fhp\FinTs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CreateBalanceTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:createTransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erstellt Transaktionen aus Zahlungseingängen für Firmen Guthaben';

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
        $account = Account::fromIban('DE91701204008492435014');
        $transactions = $account->import(now()->sub(1, 'days'), now(), true);
        if (count($transactions) > 0)
        {
            Mail::to(config('app.email'))
            ->queue(new CompanyTransactions($transactions));
        }
    }
}
