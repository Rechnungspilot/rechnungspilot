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
            'password' => Hash::make('flash3'),
            'lastname' => 'Sundermeier',
        ]);

        $user->syncRoles(1);

        $user = factory(App\User::class)->create([
            'company_id' => Company::first()->id,
            'email' => 'mussmann@mussmann-partner.net',
            'firstname' => 'Olaf',
            'password' => Hash::make('olaf'),
            'lastname' => 'Mußmann',
        ]);

        $user->syncRoles('admin');

        $user = factory(App\User::class)->create([
            'company_id' => 2,
            'email' => 'info@serienguide.tv',
            'firstname' => 'Daniel',
            'password' => Hash::make('flash3'),
            'lastname' => 'Sundermeier',
        ]);

        $user->syncRoles(2);
    }
}
