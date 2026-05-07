<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sa = User::create(
            [
                'name' => 'sa',
                'email' => 'sa@raporsd.net',
                'password' => '$2y$10$AALHMF4qCn50kXkp6XWCPehAoswBxgh8x12cqytEAxHHjZb7G4VGq'
            ]
        );

        $sa->assignRole('superadmin');
    }
}
