<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class GuruSeeder extends Seeder
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
            'read user',
            'update user',
            'read school',
            'read guru',
            'update guru',
            'add siswa',
            'read siswa',
            'update siswa',
            'delete siswa',
            'read rombel',
            'add rombel',
            'update rombel',
            'delete rombel',
            'read tp',
            'add tp',
            'update tp',
            'delete tp',
            'read atp',
            'add atp',
            'update atp',
            'delete atp',
            'read materi',
            'add materi',
            'update materi',
            'delete materi',
            'read nilai',
            'add nilai',
            'update nilai',
            'delete nilai'
        ]);
        $guru_agama->givePermissionTo([
            'read user',
            'update user',
            'read school',
            'read guru',
            'update guru',
            'read siswa',
            'read rombel',
            'read tp',
            'add tp',
            'update tp',
            'delete tp',
            'read atp',
            'add atp',
            'update atp',
            'delete atp',
            'read materi',
            'add materi',
            'update materi',
            'delete materi',
            'read nilai',
            'add nilai',
            'update nilai',
            'delete nilai'
        ]);
        $guru_pjok->givePermissionTo([
            'read user',
            'update user',
            'read school',
            'read guru',
            'update guru',
            'read siswa',
            'read rombel',
            'read tp',
            'add tp',
            'update tp',
            'delete tp',
            'read atp',
            'add atp',
            'update atp',
            'delete atp',
            'read materi',
            'add materi',
            'update materi',
            'delete materi',
            'read nilai',
            'add nilai',
            'update nilai',
            'delete nilai'
        ]);
        $guru_inggris->givePermissionTo([
            'read user',
            'update user',
            'read school',
            'read guru',
            'update guru',
            'read siswa',
            'read rombel',
            'read tp',
            'add tp',
            'update tp',
            'delete tp',
            'read atp',
            'add atp',
            'update atp',
            'delete atp',
            'read materi',
            'add materi',
            'update materi',
            'delete materi',
            'read nilai',
            'add nilai',
            'update nilai',
            'delete nilai'
        ]);
    }
}
