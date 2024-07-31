<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            "read_user",
            "add_user",
            "update_user",
            "delete_user",
            "read_school",
            "add_school",
            "update_school",
            "delete_school",
            "read_guru",
            "add_guru",
            "update_guru",
            "delete_guru",
            "add_siswa",
            "read_siswa",
            "update_siswa",
            "delete_siswa",
            "read_rombel",
            "add_rombel",
            "update_rombel",
            "delete_rombel",
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
            "read_nilai",
            "add_nilai",
            "update_nilai",
            "delete_nilai",
            "read_ops",
            "add_ops",
            "update_ops",
            "delete_ops"

        ];
        // create permission
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
