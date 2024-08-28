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

        $this->call(CabangSeeder::class);
        $this->call(RuteSeeder::class);
        $this->call(SupirSeeder::class);
        $this->call(MobilSeeder::class);
        $this->call(TitikLokasiSeeder::class);
        $this->call(MobilRentalSeeder::class);
        $this->call(FasilitasSeeder::class);
        $this->call(KebijakanHotelSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(PenginapanSeeder::class);

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
            'master_cabang_id' => 1,
            'role_id' => $superAdmin->id
        ]);

        $user->assignRole([$superAdmin->id]);
    }
}
