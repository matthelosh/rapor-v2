<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Catatan;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\NilaiEkskul;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\TanggalRapor;

class RaporService
{
    public function nilaiPTS($queries)
    {
        try {
            $sekolahId = $queries['sekolahId'];
            $nilais = [
                'pts' => Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['semester', '=', $queries['semester']],
                    ['tipe', '=', 'ts']
                ])->select('nilais.*')
                    ->with('mapel')
                    ->join('mapels', 'mapels.kode', '=', 'nilais.mapel_id')
                    ->orderBy('mapels.id')
                    ->get(),
            ];
            $tgl = TanggalRapor::where([
                ['semester', '=', $queries['semester']],
                ['tipe', '=', 'pts'],
                ['tapel', '=', $queries['tapel']],
            ])->select('tanggal')->first();
            $nilais['tanggal'] = $tgl->tanggal;
            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nilaiPAS($queries)
    {
        try {
            $rombel = Rombel::where('kode', $queries['rombelId'])->first();
            $fase = $rombel->fase;
            $sekolah = Sekolah::where('npsn', $queries['sekolahId'])->with('mapels', function ($q) use ($fase) {
                $q->where('fase', 'LIKE', '%' . $fase . '%');
            })->first();
            $mapels = $sekolah->mapels;
            $nilais = [];
            foreach ($mapels as $mapel) {
                $nas = Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'as']
                ])->first();

                $nuhs = Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'uh']
                ])->with("tp")
                    ->get();

                $avgUh =  Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'uh']
                ])->avg('skor');

                $maxUh = Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'uh']
                ])->orderBy('skor', 'DESC')->with('tp')->first();
                $minUh = Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'uh']
                ])->orderBy('skor', 'ASC')->with('tp')->first();

                $na = round(($avgUh + ($nas !== null ? $nas->skor : 0)) / 2);

                $nilais[$mapel['kode']] = [
                    'mapel' => $mapel,
                    'na' => $na,
                    'minu' => $minUh,
                    'maxu' => $maxUh
                ];
            }

            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function deskripsi($nilai)
    {
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

    public function catatan($queries)
    {
        $catatan = Catatan::where([
            ['siswa_id', '=', $queries['siswaId']],
            ['semester', '=', $queries['semester']],
            ['rombel_id', '=', $queries['rombelId']]
        ])->first();

        return $catatan ?  $catatan->teks : null;
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
