<?php

namespace App\Services;

use App\Models\Mapel;
use App\Models\Nilai;

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
            // $mapels = Mapel::whereHas('sekolah', function ($q) use ($sekolahId) {
            //     $q->where(function ($sq) use ($sekolahId) {
            //         $sq->where('npsn', $sekolahId);
            //     });
            // })->get();
            // foreach ($mapels as $mapel) {
            //     $nilais[$mapel->kode] = [
            //         'label' => $mapel->label,
            //         'nilai' => Nilai::where([
            //             ['semester', '=', $queries['semester']],
            //             ['tapel', '=', $queries['tapel']],
            //             ['mapel_id', '=', $mapel->kode],
            //             ['siswa_id', '=', $queries['siswaId']],
            //             ['tipe', '=', 'ts'],
            //             ['rombel_id','=', $queries['rombelId']]
            //         ])->first(),
            //     ];
            // }
            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
