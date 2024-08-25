<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                PermissionSeeder::class,
                RoleSeeder::class,
                SekolahSeeder::class,
                OperatorSeeder::class,
                OpsSeeder::class,
                MapelSeeder::class,
                PeriodeSeeder::class,
                PekerjaanSeeder::class,
                EkskulSeeder::class,
                RoleGuruSeeder::class,
                SiswaSeeder::class,
                PostSeeder::class,
                AdminSeeder::class,
                Agendaseeder::class,
                p5Seeder::class,
                Elemenp5Seeder::class,
                GaleriSeeder::class
            ]
        );
    }
}
