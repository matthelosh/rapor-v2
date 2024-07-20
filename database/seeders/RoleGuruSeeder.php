<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleGuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru_kelas = Role::where('name', 'guru_kelas')->first();
        $guru_agama = Role::where('name', 'guru_agama')->first();
        $guru_pjok = Role::where('name', 'guru_pjok')->first();
        $guru_inggris = Role::where('name', 'guru_inggris')->first();
        $guru_kelas->givePermissionTo([
            'read_user',
            'update_user',
            'read_school',
            'read_guru',
            'update_guru',
            'add_siswa',
            'read_siswa',
            'update_siswa',
            'delete_siswa',
            'read_rombel',
            'add_rombel',
            'update_rombel',
            'delete_rombel',
            'read_tp',
            'add_tp',
            'update_tp',
            'delete_tp',
            'read_atp',
            'add_atp',
            'update_atp',
            'delete_atp',
            'read_materi',
            'add_materi',
            'update_materi',
            'delete_materi',
            'read_nilai',
            'add_nilai',
            'update_nilai',
            'delete_nilai'
        ]);
        $guru_agama->givePermissionTo([
            'read_user',
            'update_user',
            'read_school',
            'read_guru',
            'update_guru',
            'read_siswa',
            'read_rombel',
            'read_tp',
            'add_tp',
            'update_tp',
            'delete_tp',
            'read_atp',
            'add_atp',
            'update_atp',
            'delete_atp',
            'read_materi',
            'add_materi',
            'update_materi',
            'delete_materi',
            'read_nilai',
            'add_nilai',
            'update_nilai',
            'delete_nilai'
        ]);
        $guru_pjok->givePermissionTo([
            'read_user',
            'update_user',
            'read_school',
            'read_guru',
            'update_guru',
            'read_siswa',
            'read_rombel',
            'read_tp',
            'add_tp',
            'update_tp',
            'delete_tp',
            'read_atp',
            'add_atp',
            'update_atp',
            'delete_atp',
            'read_materi',
            'add_materi',
            'update_materi',
            'delete_materi',
            'read_nilai',
            'add_nilai',
            'update_nilai',
            'delete_nilai'
        ]);
        $guru_inggris->givePermissionTo([
            'read_user',
            'update_user',
            'read_school',
            'read_guru',
            'update_guru',
            'read_siswa',
            'read_rombel',
            'read_tp',
            'add_tp',
            'update_tp',
            'delete_tp',
            'read_atp',
            'add_atp',
            'update_atp',
            'delete_atp',
            'read_materi',
            'add_materi',
            'update_materi',
            'delete_materi',
            'read_nilai',
            'add_nilai',
            'update_nilai',
            'delete_nilai'
        ]);
    }
}
