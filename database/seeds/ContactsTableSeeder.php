<?php

use App\Company;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (Company::all() as $company) {
            factory('App\Contacts\Contact', 10)->create([
                'company_id' => $company->id,
            ]);
        }
    }
}
