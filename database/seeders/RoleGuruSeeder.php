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
        $guruMapels = Role::whereNot('name', 'guru_kelas')->get();
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
            'delete_nilai',
            "add_soal",
            "read_soal",
            "update_soal",
            "delete_soal",
            'add_asesmen',
            'read_asesmen',
            'update_asesmen',
            'delete_asesmen',
            'add_proyek',
            'read_proyek',
            'update_proyek',
            'delete_proyek',
            'add_nilai_proyek',
            'read_nilai_proyek',
            'update_nilai_proyek',
            'delete_nilai_proyek',
        ]);
        foreach ($guruMapels as $guru) {
            $guru->givePermissionTo([
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
                'delete_nilai',
                "add_soal",
                "read_soal",
                "update_soal",
                "delete_soal",
                'add_asesmen',
                'read_asesmen',
                'update_asesmen',
                'delete_asesmen'
            ]);
        }
    }
}
