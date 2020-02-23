<?php

use App\Company;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            factory(App\Unit::class)->create([
                'name' => 'Stunden',
                'abbreviation' => 'h',
                'company_id' => $company->id,
            ]);

            factory(App\Unit::class)->create([
                'name' => 'Stück',
                'abbreviation' => 'stk',
                'company_id' => $company->id,
            ]);

            factory(App\Unit::class)->create([
                'name' => 'Kilometer',
                'abbreviation' => 'km',
                'company_id' => $company->id,
            ]);
        }
    }
}
