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
        $user = factory(App\User::class)->create([
            'company_id' => 1,
            'email' => 'daniel@rechnungspilot.de',
            'firstname' => 'Daniel',
            'password' => Hash::make('password'),
            'lastname' => 'Sundermeier',
        ]);

        $user->syncRoles(1);
    }
}
