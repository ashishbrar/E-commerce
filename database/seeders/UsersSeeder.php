<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Customer', 'Manager', 'Staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'is_admin' => 1,
                'role' => 'Admin',
                'password' => bcrypt('admin@gmail.com'),
            ]
        );
        $adminUser->assignRole('Admin');

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer',
                'role' => 'Customer',
                'password' => bcrypt('customer@gmail.com'),
            ]
        );
        $customerUser->assignRole('Customer');

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'role' => 'Manager',
                'password' => bcrypt('manager@gmail.com'),
            ]
        );
        $managerUser->assignRole('Manager');

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff',
                'role' => 'Staff',
                'password' => bcrypt('staff@gmail.com'),
            ]
        );
        $staffUser->assignRole('Staff');
    }
}
