<?php

use Illuminate\Database\Seeder;

class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Receipts\Receipt')->create([
            'company_id' => 1,
            'contact_id' => 1
        ]);

        factory('App\Receipts\Receipt')->create([
            'company_id' => 1,
            'contact_id' => 11
        ]);
    }
}
