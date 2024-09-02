<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_names = [
            'superadmin',
            'admin',
            'admin_tp',
            'ops',
            'kepala_sekolah',
            'guru_kelas',
            'guru_agama',
            'guru_pjok',
            'guru_inggris',
            'siswa',
            'korwil'
        ];

        foreach ($role_names as $role) {
            Role::create(['name' => $role]);
        }

        $roles = Role::all();
        $permissions = Permission::all();
        foreach ($roles as $role) {
            switch ($role->name) {
                case 'superadmin':
                    $role->givePermissionTo($permissions->pluck('name'));
                    break;
                default:
                    break;
            }
        }
    }
}
