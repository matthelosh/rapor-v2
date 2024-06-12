<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            [
                'kode' => 'pabp',
                'label' => 'Pendidikan Agama dan Budi Pekerti',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Pendidikan Agama dan Budi Pekerti'
            ],
            [
                'kode' => 'pkn',
                'label' => 'Pendidikan Pancasila',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Pendidikan Pancasila'
            ],
            [
                'kode' => 'bind',
                'label' => 'Bahasa Indonesia',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Bahasa Indonesia'
            ],
            [
                'kode' => 'mtk',
                'label' => 'Matematika',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Matematika'
            ],
            [
                'kode' => 'ipas',
                'label' => 'Ilmu Pengetahuan Alam dan Sosial',
                'fase' => 'B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Ilmu Pengetahuan Alam dan Sosial',
            ],
            [
                'kode' => 'pjok',
                'label' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Pendidikan Jasmani, Olahraga dan Kesehatan'
            ],
            [
                'kode' => 'sm',
                'label' => 'Seni Musik',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Seni Musik'
            ],
            [
                'kode' => 'sr',
                'label' => 'Seni Rupa',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Seni Rupa'
            ],
            [
                'kode' => 'ste',
                'label' => 'Seni Teater',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Seni Teater'
            ],
            [
                'kode' => 'str',
                'label' => 'Seni Tari',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Seni Tari'
            ],
            [
                'kode' => 'bing',
                'label' => 'Bahsasa Inggris',
                'fase' => 'A,B,C',
                'kategori' => 'Wajib',
                'deskripsi' => 'Bahasa Inggris'
            ],
            [
                'kode' => 'bj',
                'label' => 'Bahsasa Jawa',
                'fase' => 'A,B,C',
                'kategori' => 'Mulok',
                'deskripsi' => 'Bahasa Jawa'
            ],
        ];
        
        foreach ($mapels as $mapel ) 
        {
            $newMapel = new Mapel($mapel);
            $newMapel->save();
        }
    }
}
