<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('module-const.menu');
        $allPermissions = [];
        foreach ($modules as $module) {
            foreach ($module['roles'] as $permissionName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
                $allPermissions[] = $permission;
            }
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin'], ['guard_name' => 'web']);

        $adminRole->syncPermissions($allPermissions);
        foreach ($modules as $module) {
            $roleName = $module['name'];
            if (strtolower($roleName) === 'admin') continue;
            $permissions = Permission::whereIn('name', $module['roles'])->get();
        }

    }
}
