<?php

use App\Company;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $companies = Company::all();

        $actions = [
            'view',
            'create',
            'update',
            'delete',
        ];

        $groups = [
            'contacts',
            'invoices',
            'companies',
            'items',
            'users',
        ];

        foreach ($groups as $group) {
            foreach ($actions as $action) {
                Permission::create([
                    'action' => $action,
                    'group' => $group,
                    'name' => $action . ' ' . $group,
                ]);
            }
        }

        foreach ($companies as $company) {
            $role = Role::create([
                'company_id' => $company->id,
                'name' => 'admin',
            ]);
            $role->givePermissionTo(Permission::all());
        }
    }
}
