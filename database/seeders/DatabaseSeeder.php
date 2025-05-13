<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan seeder modular
        $this->call([
            CabangSeeder::class,
            RuteSeeder::class,
            SupirSeeder::class,
            MobilSeeder::class,
            TitikLokasiSeeder::class,
            FasilitasMobilRentalSeeder::class,
            MobilRentalSeeder::class,
            FasilitasSeeder::class,
            KebijakanHotelSeeder::class,
            ImageSeeder::class,
            PenginapanSeeder::class,
            RoleAndPermissionSeeder::class,
        ]);

        // Ambil role Super Admin yang sudah dibuat di RoleAndPermissionSeeder
        $superAdmin = Role::where('name', 'Super Admin')->where('guard_name', 'api')->first();

        // Ambil semua permissions
        $permissions = Permission::pluck('name')->toArray();

        // Buat user admin
        $user = User::firstOrCreate(
            ['email' => 'admin@mailinator.com'],
            [
                'nama' => 'admin',
                'password' => Hash::make('password'),
                'master_cabang_id' => 1,
                'role_id' => $superAdmin->id
            ]
        );

        // Assign role & permissions
        $user->assignRole($superAdmin);
        $user->syncPermissions($permissions);
    }
}
