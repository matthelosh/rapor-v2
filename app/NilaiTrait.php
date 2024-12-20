<?php

namespace App;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Tapel;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tp;
use Illuminate\Support\Facades\DB;

trait NilaiTrait
{
    public function simpanNilai($request)
    {
        try {
            $siswas = $request->siswas;
            // dd($siswas);
            $query = $request->query();
            foreach ($siswas as $siswa) {
                if ($query['tipe'] == 'uh') {
                    foreach ($siswa['nilais'] as $k => $v) {
                        $tp = Tp::whereKode($k)->first();
                        if ($v !== null || $tp) {
                            $store = Nilai::updateOrCreate(
                                [
                                    'tapel' => $query['tapel'],
                                    'semester' => $query['semester'],
                                    'siswa_id' => $siswa['nisn'],
                                    'guru_id' => auth()->user()->userable->nip,
                                    'rombel_id' => $query['rombelId'],
                                    'mapel_id' => $query['mapelId'],
                                    'agama' => $query['agama'] ?? null,
                                    'tp_id' => $k,
                                    'tipe' =>  'uh',
                                ],
                                [
                                    'skor' => $v !== 'null' ? $v : 0
                                ]
                            );
                        }
                    }
                } elseif ($query['tipe'] == 'all') {
                    foreach ($siswa['nilais'] as $k => $v) {
                        $tp = Tp::whereKode($k)->first();
                        if ($v !== null || $tp) {
                            $store = Nilai::updateOrCreate(
                                [
                                    'tapel' => $query['tapel'],
                                    'semester' => $query['semester'],
                                    'siswa_id' => $siswa['nisn'],
                                    'guru_id' => auth()->user()->userable->nip,
                                    'rombel_id' => $query['rombelId'],
                                    'mapel_id' => $query['mapelId'],
                                    'agama' => $query['agama'] ?? null,
                                    'tp_id' => \in_array($k, ['ts', 'as']) ? null : $k,
                                    'tipe' => \in_array($k, ['ts', 'as']) ? $k : 'uh',
                                ],
                                [
                                    'skor' => $v !== 'null' ? $v : 0
                                ]
                            );
                        }
                    }
                } else {
                    $store = Nilai::updateOrCreate(
                        [
                            'tapel' => $query['tapel'],
                            'semester' => $query['semester'],
                            'siswa_id' => $siswa['nisn'],
                            'guru_id' => auth()->user()->userable->nip,
                            'rombel_id' => $query['rombelId'],
                            'mapel_id' => $query['mapelId'],
                            'agama' => $query['agama'] ?? null,
                            'tp_id' => null,
                            'tipe' => $query['tipe'],
                        ],
                        [
                            'skor' => $siswa['nilai']
                        ]
                    );
                }
            }

            return 'Nilai Disimpan';
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function indexNilai($request)
    {
        try {
            $query = $request->query();
            $nilais = Nilai::where([
                ['tapel', '=', $query['tapel']],
                ['semester', '=', $query['semester']],
                ['rombel_id', '=', $query['rombelId']],
                ['agama', '=', $query['mapelId'] == 'pabp' ? $query['agama'] : null],
                ['tipe', '=', $query['tipe']],
                ['mapel_id', '=', $query['mapelId']]
            ])->get();

            return $nilais;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function ledger($request)
    {
        $user = $request->user();
        if ($user->hasRole('guru_kelas')) {
            $rombel = Rombel::where('guru_id', $request->user()->userable->id)->whereTapel($this->periode()['tapel']['kode'])->with('siswas')->first();
            $nilais = Nilai::where([
                ['tapel', '=', $this->periode()['tapel']['kode']],
                ['semester', '=', $this->periode()['tapel']['semester']],
                ['rombel_id', '=', $rombel->kode]
            ])
                // ->select("")
                // ->selectRaw("AVG(skor) AS rerata")
                // ->groupBy('siswa_id','mapel_id')
                ->get();

            $npsn = $request->user()->userable->sekolahs[0]->npsn;
            $datas = [];

            $mapels = $this->mapels($request->user(), $rombel);
            // siswa = [
            //      'nilais' => [
            //          'pabp' => ['sem1' => 90, 'sem2' => 98]
            //      ]
            // ]
            foreach ($rombel->siswas as $siswa) {
                $data = [];
                $data['nisn'] = $siswa->nisn;
                $data['nama'] = $siswa->nama;
                $sum1 = 0;
                $sum2 = 0;
                foreach ($mapels as $mapel) {
                    $nas1 = Nilai::where([
                        ['siswa_id', '=', $siswa->nisn],
                        ['rombel_id', '=', $rombel->kode],
                        ['tapel', '=', $this->periode()['tapel']['kode']],
                        ['semester', '=', '1'],
                        ['mapel_id', '=', $mapel->kode],
                        ['tipe', '=', 'as']
                    ])->first();

                    $avgUh1 =  Nilai::where([
                        ['siswa_id', '=', $siswa->nisn],
                        ['rombel_id', '=', $rombel->kode],
                        ['tapel', '=', $this->periode()['tapel']['kode']],
                        ['semester', '=', '1'],
                        ['mapel_id', '=', $mapel->kode],
                        ['tipe', '=', 'uh']
                    ])->avg('skor');

                    $na1 = round(($avgUh1 + ($nas1 !== null ? $nas1->skor : 0)) / 2);
                    $nas2 = Nilai::where([
                        ['siswa_id', '=', $siswa->nisn],
                        ['rombel_id', '=', $rombel->kode],
                        ['tapel', '=', $this->periode()['tapel']['kode']],
                        ['semester', '=', '2'],
                        ['mapel_id', '=', $mapel->kode],
                        ['tipe', '=', 'as']
                    ])->first();

                    $avgUh2 =  Nilai::where([
                        ['siswa_id', '=', $siswa->nisn],
                        ['rombel_id', '=', $rombel->kode],
                        ['tapel', '=', $this->periode()['tapel']['kode']],
                        ['semester', '=', '2'],
                        ['mapel_id', '=', $mapel->kode],
                        ['tipe', '=', 'uh']
                    ])->avg('skor');

                    $na1 = round(($avgUh1 + ($nas1 !== null ? $nas1->skor : 0)) / 2);
                    $na2 = round(($avgUh2 + ($nas2 !== null ? $nas2->skor : 0)) / 2);
                    $sum1 += $na1;
                    $sum2 += $na2;
                    $data[$mapel->kode] = [
                        'sem1' => $na1,
                        'sem2' => $na2,
                    ];
                }
                $data['sum1'] = $sum1;
                $data['sum2'] = $sum2;
                array_push($datas, $data);
            }
        } else {
            $datas = [];
        }

        return $datas;
    }

    public function prosentase($user)
    {
        $userId = $user->id;
        $guruId = $user->userable->id;
        $tapel = $this->periode()['tapel']->kode;
        try {
            // If Guru !== Guru Kelas dan MErangkap
            $res = [];
            if (!$user->hasRole('guru_kelas')) {
                $sekolahs = Sekolah::whereHas('gurus', function ($g) use ($guruId) {
                    $g->where('gurus.id', $guruId);
                })->with('rombels', function ($r) use ($tapel) {
                    $r->where('tapel', $tapel);
                    $r->with('siswas');
                })->get();

                foreach ($sekolahs[0]->rombels as $rombel) {
                    \array_push($res, [
                        'rombel' => $rombel->kode,
                        'nilais' => [
                            'pts' => Nilai::where('rombel_id', $rombel->kode)->where('tipe', 'ts')->where('mapel_id', 'pabp')->where('semester', $this->periode()['semester']->kode)->count(),
                            'uhs' => Nilai::where('rombel_id', $rombel->kode)->where('tipe', 'uh')->where('mapel_id', 'pabp')->where('semester', $this->periode()['semester']->kode)->count(),
                            'pas' => Nilai::where('rombel_id', $rombel->kode)->where('tipe', 'as')->where('mapel_id', 'pabp')->where('semester', $this->periode()['semester']->kode)->count(),
                        ]
                    ]);
                }
            }
            // Else Guru KElas
            else {
            }

            return $res;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function mapels($user, $rombel)
    {
        $npsn = $user->userable->sekolahs[0]->npsn;
        return Mapel::whereHas('sekolah', function ($q) use ($npsn) {
            $q->where('npsn', $npsn);
        })->where('fase', 'LIKE', '%' . $rombel->fase . '%')->get();
    }
    private function periode()
    {
        return [
            'semester' => Semester::whereIsActive(1)->first(),
            'tapel' => Tapel::whereisActive(1)->first()
        ];
    }
}
