<?php

use App\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_id = 1;

        $user = factory(App\User::class)->create([
            'company_id' => $company_id,
            'email' => 'daniel@rechnungspilot.de',
            'firstname' => 'Daniel',
            'password' => Hash::make('password'),
            'lastname' => 'Sundermeier',
        ]);

        $user->syncRoles(1);

        $user->companies()->attach($company_id);
    }
}
