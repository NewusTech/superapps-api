<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MasterCabang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    private $permissions = [
        'create',
        'update',
        'read',
        'delete'
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $cabang = MasterCabang::factory()->create();

        // Create admin User and assign the role to him.
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $roleCustomer = Role::create(['name' => 'Customer']);

        $permissions = Permission::pluck('id', 'id')->all();

        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions($permissions);

        $user = User::create([
            'nama' => 'admin',
            'email' => 'admin@mailinator.com',
            'password' => Hash::make('password'),
            'master_cabang_id' => $cabang->id,
            'role_id' => $superAdmin->id
        ]);

        $user->assignRole([$superAdmin->id]);

    }
}
