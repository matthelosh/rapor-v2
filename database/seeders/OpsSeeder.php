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
            'read user',
            'add user',
            'update user',
            'delete user',
            'read school',
            'update school',
            'read guru',
            'add guru',
            'update guru',
            'delete guru',
            'add siswa',
            'read siswa',
            'update siswa',
            'delete siswa',
            'read rombel',
            'add rombel',
            'update rombel',
            'delete rombel',
            'read tapel',
            'read semester',
            'read mapel',
            'add mapel',
            'update mapel',
            'delete mapel',
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
        ]);
    }
}
