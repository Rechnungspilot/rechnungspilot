<?php

use App\Company;
use App\Unit;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = Unit::all();

        foreach (Company::all() as $company) {
            factory('App\Item')->create([
                'name' => 'Beratung / Coaching',
                'unit_price' => 1665.00,
                'unit_id' => $units->where('name', 'Stück')->where('company_id', $company->id)->first()->id,
                'company_id' => $company->id,
            ]);

            factory('App\Item')->create([
                'name' => 'Tageswerke',
                'unit_price' => 1665.00,
                'unit_id' => $units->where('name', 'Stück')->where('company_id', $company->id)->first()->id,
                'company_id' => $company->id,
            ]);

            factory('App\Item')->create([
                'name' => 'Fahrtkosten',
                'unit_price' => 0.52,
                'unit_id' => $units->where('name', 'Kilometer')->where('company_id', $company->id)->first()->id,
                'company_id' => $company->id,
            ]);

            factory('App\Item')->create([
                'name' => 'Übernachtung',
                'unit_price' => 0,
                'unit_id' => $units->where('name', 'Stück')->where('company_id', $company->id)->first()->id,
                'company_id' => $company->id,
            ]);
        }
    }
}
