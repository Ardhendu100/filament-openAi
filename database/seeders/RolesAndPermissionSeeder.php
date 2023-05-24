<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $policies = ['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'];
        $models = ['message_analytics', 'users', 'permissions', 'roles'];
        foreach ($policies as $policy) {
            foreach ($models as $model) {
                Permission::create([
                    'name' => "{$policy} {$model}",
                    'guard_name' => "web",
                ]);
            }
        }
        Permission::create([
            'name' => "manage settings",
            'guard_name' => "web",
        ]);

        Role::create(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'api-user'])
            ->givePermissionTo([
                'viewAny message_analytics',
                'view message_analytics',
                'create message_analytics',
                'update message_analytics',
                'delete message_analytics',
            ]);
    }
}
