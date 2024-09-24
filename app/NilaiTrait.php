<?php

namespace App;

use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Tapel;
use App\Models\Rombel;
use App\Models\Semester;

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
                        if ($v !== null) {
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
                                    'tipe' => $query['tipe'],
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

                    $data[$mapel->kode] = [
                        'sem1' => $na1,
                        'sem2' => $na2,
                    ];
                }

                array_push($datas, $data);
            }
        } else {
            $datas = [];
        }

        return $datas;
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
