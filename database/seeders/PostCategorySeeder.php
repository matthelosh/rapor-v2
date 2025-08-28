<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            'Berita',
            'Tutorial',
            'Pengumuman',
            'Profil'
        ];
        foreach($datas as $data)
        {
            PostCategory::create(['name' => $data]);
        }
    }
}
