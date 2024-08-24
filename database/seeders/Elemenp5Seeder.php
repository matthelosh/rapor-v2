<?php

namespace Database\Seeders;

use App\Models\Elemenp5;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Elemenp5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elemens = [
            [
                'dimensi_id' => 'D1',
                'teks' => 'Akhlak beragama'
            ],
            [
                'dimensi_id' => 'D1',
                'teks' => 'Akhlak pribadi'
            ],
            [
                'dimensi_id' => 'D1',
                'teks' => 'Akhlak kepada manusia'
            ],
            [
                'dimensi_id' => 'D1',
                'teks' => 'Akhlak kepada alam'
            ],
            [
                'dimensi_id' => 'D1',
                'teks' => 'Akhlak bernegara'
            ],
            [
                'dimensi_id' => 'D2',
                'teks' => 'Mengenal dan menghargai budaya'
            ],
            [
                'dimensi_id' => 'D2',
                'teks' => 'Komunikasi dan interaksi antar budaya'
            ],
            [
                'dimensi_id' => 'D2',
                'teks' => 'Refleksi dan tanggung jawab terhadap pengalaman kebinekaan'
            ],
            [
                'dimensi_id' => 'D2',
                'teks' => 'Berkeadilan Sosial'
            ],
            [
                'dimensi_id' => 'D3',
                'teks' => 'Kolaborasi'
            ],
            [
                'dimensi_id' => 'D3',
                'teks' => 'Kepedulian'
            ],
            [
                'dimensi_id' => 'D3',
                'teks' => 'Berbagi'
            ],
            [
                'dimensi_id' => 'D4',
                'teks' => 'Pemahaman diri dan situasi yang dihadapi'
            ],
            [
                'dimensi_id' => 'D4',
                'teks' => 'Regulasi diri'
            ],
            [
                'dimensi_id' => 'D5',
                'teks' => 'Memperoleh informasi dan memproses informasi dan gagasan'
            ],
            [
                'dimensi_id' => 'D5',
                'teks' => 'Menganalisis dan mengevaluasi penalaran'
            ],
            [
                'dimensi_id' => 'D5',
                'teks' => 'Refleksi Pemikiran dan Proses Berfikir'
            ],
            [
                'dimensi_id' => 'D6',
                'teks' => 'Menghasilkan gagasan yang orisinil'
            ],
            [
                'dimensi_id' => 'D6',
                'teks' => 'Menghasilkan karya dan tindakan yang orisinal'
            ],
            [
                'dimensi_id' => 'D6',
                'teks' => 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan'
            ],

        ];

        foreach ($elemens as $elemen) {
            Elemenp5::create(
                [
                    'dimensi_id' => $elemen['dimensi_id'],
                    'teks' => $elemen['teks']
                ]
            );
        }
    }
}
