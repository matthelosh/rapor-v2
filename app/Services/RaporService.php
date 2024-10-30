<?php

namespace App\Services;

use App\Helpers\Periode;
use App\Models\Absensi;
use App\Models\Catatan;
use App\Models\Kktp;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\NilaiEkskul;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\TanggalRapor;
use Illuminate\Support\Facades\Auth;

class RaporService
{
    public function nilaiPTS($queries)
    {
        try {
            $sekolahId = $queries['sekolahId'];
            $sekolah = Sekolah::whereNpsn($sekolahId)
                ->with(
                    [
                        'ekskuls',
                        'mapels' => fn($m) => $m->orderBy('id', 'ASC'),
                    ]
                )->first();
            $mapels = $sekolah->mapels;
            $siswa = Siswa::whereNisn($queries['siswaId'])->first();
            $nilais = ['pts' => []];
            foreach ($mapels as $mapel) {
                $npts = Nilai::where([
                    ['siswa_id', '=', $queries['siswaId']],
                    ['rombel_id', '=', $queries['rombelId']],
                    ['tapel', '=', $queries['tapel']],
                    ['semester', '=', $queries['semester']],
                    ['mapel_id', '=', $mapel['kode']],
                    ['tipe', '=', 'ts']
                ])
                    ->with('mapel')
                    ->first();

                \array_push(
                    $nilais['pts'],
                    $npts ??
                        [
                            'tapel' => Periode::tapel(),
                            'semester' => Periode::semester(),
                            'siswa_id' => $queries['siswaId'],
                            'guru_id' => Auth::user()->userable->nip,
                            'rombel_id' => $queries['rombelId'],
                            'mapel_id' => $mapel->kode,
                            'agama' => $mapel->kode == 'pabp' ? $siswa->agama : null,
                            'tipe' => 'ts',
                            'skor' => 0,
                            'mapel' => $mapel
                        ]
                );
            }



            // $nilais = [
            //     'pts' => Nilai::where([
            //         ['siswa_id', '=', $queries['siswaId']],
            //         ['rombel_id', '=', $queries['rombelId']],
            //         ['semester', '=', $queries['semester']],
            //         ['tipe', '=', 'ts']
            //     ])->select('nilais.*')
            //         ->with('mapel')
            //         ->join('mapels', 'mapels.kode', '=', 'nilais.mapel_id')
            //         ->orderBy('mapels.id')
            //         ->get(),
            // ];
            $tgl = TanggalRapor::where([
                ['semester', '=', $queries['semester']],
                ['tipe', '=', 'pts'],
                ['tapel', '=', $queries['tapel']],
            ])->select('tanggal')->first();
            $nilais['tanggal'] = $tgl ? $tgl->tanggal : date('Y-M-d');
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
            $npsn = $sekolah->npsn;
            $mapels = Mapel::whereHas('sekolah', function ($q) use ($npsn) {
                $q->where('npsn', $npsn);
            })
                ->where('fase', 'LIKE', '%' . $rombel->fase . '%')
                ->orderBy('id', 'ASC')
                ->get();
            $nilais = [];
            $nomor = 0;
            foreach ($mapels as $mapel) {
                $nomor++;
                $kktp = Kktp::where('rombel_id', $rombel->kode)
                    ->where('mapel_id', $mapel->kode)
                    ->where('sekolah_id', $sekolah->npsn)
                    ->where('tapel', $queries['tapel'])
                    ->where('semester', $queries['semester'])
                    ->first();

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
                    'nomor' => $nomor,
                    'mapel' => $mapel,
                    'na' => $na,
                    'minu' => $minUh,
                    'maxu' => $maxUh,
                    'kktp' => $kktp
                ];
            }

            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function deskripsi($nilai) {}

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
