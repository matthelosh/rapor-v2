<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ops = Role::where('name', 'ops')->first();
        $ops->givePermissionTo([
            'read_user',
            'add_user',
            'update_user',
            'delete_user',
            'read_school',
            'update_school',
            'read_guru',
            'add_guru',
            'update_guru',
            'delete_guru',
            'add_siswa',
            'read_siswa',
            'update_siswa',
            'delete_siswa',
            'read_rombel',
            'add_rombel',
            'update_rombel',
            'delete_rombel',
            'read_tapel',
            'read_semester',
            'read_mapel',
            'add_mapel',
            'update_mapel',
            'delete_mapel',
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
        ]);
    }
}
