<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class CompanyChargeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monatliche Abrechnung durchführen';

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
        $companies = Company::whereDate('charging_next_at', today())->get();
        foreach ($companies as $key => $company) {
            $company->charge();
        }
    }
}
