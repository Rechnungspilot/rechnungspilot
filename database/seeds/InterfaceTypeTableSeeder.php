<?php

use Illuminate\Database\Seeder;

class InterfaceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            $names = [
                'E-Mail',
                'Persönliches Gespräch',
                'Telefonat',
            ];
            foreach ($names as $key => $value) {
                factory(InterfaceType::class)->create([
                    'name' => $name,
                    'company_id' => $company->id,
                ]);
            }
        }
    }
}
