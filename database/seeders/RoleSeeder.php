<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_names = [
            'admin',
            'ops',
            'kepala_sekolah',
            'guru_kelas',
            'guru_agama',
            'guru_pjok',
            'guru_inggris',
            'siswa'
        ];

        foreach($role_names as $role) 
        {
            Role::create(['name' => $role]);
        }

        $roles = Role::all();
        foreach($roles as $role)
        {
            switch($role->name)
            {
                case 'admin':
                    $role->givePermissionTo($roles);
                    break;
                default:
                    break;
            }
        }
    }
}
