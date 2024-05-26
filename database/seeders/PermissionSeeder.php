<?php

namespace Database\Seeders;

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
            'read user',
            'add user',
            'update user',
            'delete user',
            'read school',
            'add school',
            'update school',
            'delete school',
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
            'add tapel',
            'update tapel',
            'delete tapel',
            'read semester',
            'add semester',
            'update semester',
            'delete semester',
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
            'add nilai',
            'update nilai',
            'delete nilai',

        ];
        // create permission
        foreach ($permissions as $permission)
        {
            Permission::create([ 'name' => $permission]);
        }
    }
}
