<?php

namespace Database\Seeders;

use App\Models\Sertifikat;
use App\Models\Workshop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workshops = Workshop::all();

        foreach ($workshops as $ws) {
            Sertifikat::create(
                [
                    'nomor' => Str::random(16),
                    'workshop_id' => $ws->id,
                    'guru_id' => '198407032019031007',
                    'jp' => 32,
                    'deskripsi' => 'Sertifikat diberkan sebagai apresiasi karena telah mengikuti kegiatan ' . $ws->nama . ' pada tanggal ' . Carbon::createFromDate($ws->mulai)->format('d M Y') . ' s/d. ' . Carbon::createFromDate($ws->selesai)->format('d M Y') . ' yang diselenggarkan oleh ' . $ws->pelaksana,
                    'template' => null,
                    'arsip' => null,
                ]
            );
        }
    }
}
