<?php

use App\Company;
use App\Receipts\Abos\Abo;
use App\Receipts\Delivery;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Order;
use App\Receipts\Quote;
use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = [ 0, 7, 14, 21, 28 ];

        foreach (Company::all() as $company) {
            foreach ($terms as $key => $days) {
                factory('App\Receipts\Term')->create([
                    'company_id' => $company->id,
                    'name' => $days . ' Tage',
                    'days' => $days,
                    'default' => ($key == 0),
                ]);
            }

            foreach ($terms as $key => $days) {
                factory('App\Receipts\Term')->create([
                    'company_id' => $company->id,
                    'name' => $days . ' Tage',
                    'days' => $days,
                    'default' => ($key == 0),
                    'type' => Quote::class,
                ]);
            }

            foreach ($terms as $key => $days) {
                factory('App\Receipts\Term')->create([
                    'company_id' => $company->id,
                    'name' => $days . ' Tage',
                    'days' => $days,
                    'default' => ($key == 0),
                    'type' => Expense::class,
                ]);
            }

            factory('App\Receipts\Term')->create([
                'company_id' => $company->id,
                'name' => 'heute',
                'days' => 0,
                'default' => true,
                'type' => Order::class,
            ]);

            factory('App\Receipts\Term')->create([
                'company_id' => $company->id,
                'name' => 'heute',
                'days' => 0,
                'default' => true,
                'type' => Delivery::class,
            ]);

            factory('App\Receipts\Term')->create([
                'company_id' => $company->id,
                'name' => 'heute',
                'days' => 0,
                'default' => true,
                'type' => Income::class,
            ]);

            factory('App\Receipts\Term')->create([
                'company_id' => $company->id,
                'name' => 'heute',
                'days' => 0,
                'default' => true,
                'type' => Abo::class,
            ]);
        }
    }
}
