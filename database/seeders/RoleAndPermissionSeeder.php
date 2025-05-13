<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'api']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'api']);

        Permission::firstOrCreate([
            'name' => 'jadwal.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'jadwal.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'jadwal.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'jadwal.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'paket.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'paket.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'paket.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'paket.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rental.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rental.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rental.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rental.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'mobil.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'mobil.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'mobil.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'mobil.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penumpang.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penumpang.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penumpang.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penumpang.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'users.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'users.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'users.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'users.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'laporan.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'laporan.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'laporan.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'laporan.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pembayaran.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pembayaran.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pembayaran.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pembayaran.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'supir.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'supir.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'supir.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'supir.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'cabang.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'cabang.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'cabang.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'cabang.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rute.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rute.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rute.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'rute.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'kursi.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'kursi.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'kursi.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'kursi.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penginapan.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penginapan.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penginapan.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'penginapan.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'titik_jemput.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'titik_jemput.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'titik_jemput.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'titik_jemput.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pariwisata.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pariwisata.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pariwisata.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'pariwisata.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'banner.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'banner.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'banner.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'banner.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'artikel.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'artikel.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'artikel.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'artikel.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'printer.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'printer.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'printer.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'printer.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'konsumen.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'konsumen.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'konsumen.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'konsumen.delete',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'roles.create',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'roles.read',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'roles.update',
            'guard_name' => 'api'
        ]);
        Permission::firstOrCreate([
            'name' => 'roles.delete',
            'guard_name' => 'api'
        ]);

        // Super Admin dapat semua permission
        $superAdmin->givePermissionTo(Permission::all());

        // Admin dapat sebagian permission (misalnya semua modul)
        $admin->givePermissionTo([
            'jadwal.create', 'jadwal.read', 'jadwal.update', 'jadwal.delete',
            'paket.create', 'paket.read', 'paket.update', 'paket.delete',
            'rental.read', 'rental.update',
            'mobil.read', 'mobil.update',
            'penumpang.read', 'penumpang.update',
            'users.read',
            'laporan.read',
            'pembayaran.read',
            'supir.read',
            'cabang.read',
            'rute.read',
            'kursi.read',
            'penginapan.read',
            'titik_jemput.read',
        ]);
    }
}
