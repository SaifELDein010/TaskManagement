<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'api',
        ]);

        $member = Role::firstOrCreate([
            'name' => 'Member',
            'guard_name' => 'api',
        ]);

        $superAdmin->givePermissionTo([
            'manage-user',
            'manage-role'
        ]);

        $member->givePermissionTo([
            'create-task',
        ]);
    }
}