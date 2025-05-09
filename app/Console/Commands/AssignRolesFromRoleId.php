<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesFromRoleId extends Command
{
    protected $signature = 'fix:assign-roles';
    protected $description = 'Assign ulang role ke semua user berdasarkan field role_id (untuk Spatie Permission)';

    public function handle()
    {
        $users = User::with('roles')->get();
        $assigned = 0;
        $skipped = 0;

        foreach ($users as $user) {
            if ($user->roles->isEmpty() && $user->role_id) {
                $role = Role::find($user->role_id);
                if ($role) {
                    $user->assignRole($role->name);
                    $this->info("✔ Assigned role '{$role->name}' ke user: {$user->email}");
                    $assigned++;
                } else {
                    $this->warn("⚠ Role ID {$user->role_id} tidak ditemukan untuk user: {$user->email}");
                }
            } else {
                $skipped++;
            }
        }

        $this->line("Selesai. {$assigned} role ditambahkan, {$skipped} user dilewati.");
        return 0;
    }
}
