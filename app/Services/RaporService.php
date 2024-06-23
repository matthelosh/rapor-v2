<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\NilaiEkskul;

class RaporService
{
    public function nilaiPTS($queries)
    {
        try {
            $sekolahId = $queries['sekolahId'];
            // $nilais = [];
            $nilais = Nilai::where([
                ['siswa_id', '=', $queries['siswaId']],
                ['rombel_id', '=', $queries['rombelId']],
                ['semester', '=', $queries['semester']],
                ['tipe', '=', 'ts']
            ])->select('nilais.*')
                ->with('mapel')
                ->join('mapels', 'mapels.kode', '=', 'nilais.mapel_id')
                ->orderBy('mapels.id')
                ->get();
            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nilaiPAS($request)
    {
        try {
            //code...
            return ['Halo PAS'];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function absensi($queries)
    {
        try {
            return Absensi::where([
                ['siswa_id', '=', $queries['siswaId']],
                ['semester', '=', $queries['semester']],
                ['rombel_id', '=', $queries['rombelId']]
            ])->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function ekskul($queries)
    {
        try {
            return NilaiEkskul::where([
                ['tapel', '=', $queries['tapel']],
                ['semester', '=', $queries['semester']],
                ['siswa_id', '=', $queries['siswaId']]
            ])->with('ekskul')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
