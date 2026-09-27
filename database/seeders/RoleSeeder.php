<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void {
        Role::updateOrCreate([
            'name' => 'Super Admin',
            'description' => 'Has full access to the entire system',
        ]);

        Role::updateOrCreate([
            'name' => 'Admin',
            'description' => 'Has full access within assigned workspaces',
        ]);

        Role::updateOrCreate([
            'name' => 'Project Owner',
            'description' => 'Has full access within assigned folders',
        ]);

        Role::updateOrCreate([
            'name' => 'Project Member',
            'description' => 'Has access to tasks within permitted resources',
        ]);
    }
}
