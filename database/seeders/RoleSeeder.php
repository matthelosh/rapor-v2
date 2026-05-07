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
            'korwil',
            'org'
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
                case "admin":
                    $role->givePermissionTo([
                        "read_user",
                        "add_user",
                        "update_user",
                        "delete_user",
                        "read_school",
                        "add_school",
                        "update_school",
                        "delete_school",
                        "read_guru",
                        "read_tapel",
                        "add_tapel",
                        "update_tapel",
                        "delete_tapel",
                        "read_semester",
                        "add_semester",
                        "update_semester",
                        "delete_semester",
                        "read_mapel",
                        "add_mapel",
                        "update_mapel",
                        "delete_mapel",
                        "read_tp",
                        "add_tp",
                        "update_tp",
                        "delete_tp",
                        "read_atp",
                        "add_atp",
                        "update_atp",
                        "delete_atp",
                        "read_materi",
                        "add_materi",
                        "update_materi",
                        "delete_materi",
                        "read_ops",
                        "add_ops",
                        "update_ops",
                        "delete_ops",
                        'add_workshop',
                        'read_workshop',
                        'update_workshop',
                        'delete_workshop',
                        'add_workshop_member',
                        'update_workshop_member',
                        'read_workshop_member',
                        'delete_workshop_member',
                        "add_soal",
                        "read_soal",
                        "update_soal",
                        "delete_soal",
                        'add_asesmen',
                        'read_asesmen',
                        'update_asesmen',
                        'delete_asesmen',
                    ]);
                    break;
                case "admin_tp":
                    $role->givePermissionTo([
                        "read_tp",
                        "add_tp",
                        "update_tp",
                        "delete_tp",
                        "read_atp",
                        "add_atp",
                        "update_atp",
                        "delete_atp",
                        "read_materi",
                        "add_materi",
                        "update_materi",
                        "delete_materi",
                    ]);
                    break;
                case "org";
                    $role->givePermissionTo([
                        'add_workshop',
                        'read_workshop',
                        'update_workshop',
                        'delete_workshop',
                        'add_workshop_member',
                        'update_workshop_member',
                        'read_workshop_member',
                        'delete_workshop_member',
                        "add_soal",
                        "read_soal",
                        "update_soal",
                        "delete_soal",
                        'add_asesmen',
                        'read_asesmen',
                        'update_asesmen',
                        'delete_asesmen',
                    ]);
                default:
                    break;
            }
        }
    }
}
