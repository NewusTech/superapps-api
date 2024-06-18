<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MasterCabang;
use App\Models\MasterMobil;
use App\Models\MasterRute;
use App\Models\MasterSupir;
use App\Models\User;
use Carbon\Carbon;
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

        $cabang = MasterCabang::create([
            'nama' => 'Lampung',
        ]);

        // Create seeders for all Master tables
        $dataSupirSeeder =  [[
            'nama' => 'Heri',
            'no_telp' => '0812345678912'
        ], [
            'nama' => 'Budi',
            'no_telp' => '084141512912'
        ]];
        foreach ($dataSupirSeeder as $item) {
            MasterSupir::create($item);
        };

        $dataRuteSeeder =  [
            [
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Palembang',
                'harga' => 250000,
            ], [
                'kota_asal' => 'Palembang',
                'kota_tujuan' => 'Lampung',
                'harga' => 250000,
            ]
        ];
        foreach ($dataRuteSeeder as $item) {
            MasterRute::create($item);
        }


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
