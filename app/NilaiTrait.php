<?php

namespace App;

use App\Models\Nilai;
use App\Models\Tapel;
use App\Models\Rombel;
use App\Models\Semester;

trait NilaiTrait
{
    public function simpanNilai($request) {
        try {
            $siswas = $request->siswas;
            // dd($siswas);
            $query = $request->query();
            foreach($siswas as $siswa) 
            {
                if ($query['tipe'] == 'uh') {
                    foreach($siswa['nilais'] as $k=>$v)
                    {
                        if ($v !== null) {
                        $store = Nilai::updateOrCreate(
                            [
                                'tapel' => $query['tapel'],
                                'semester' => $query['semester'],
                                'siswa_id' => $siswa['nisn'],
                                'guru_id' => auth()->user()->userable->nip,
                                'rombel_id' => $query['rombelId'],
                                'mapel_id' => $query['mapelId'],
                                'agama' => $query['agama'],
                                'tp_id' => $k,
                                'tipe' => $query['tipe'],
                            ], [
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
                            'agama' => $query['agama']??null,
                            'tp_id' => null,
                            'tipe' => $query['tipe'],
                        ], [
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

    public function indexNilai($request) {
        try {
            $query = $request->query();
            $nilais = Nilai::where([
                ['tapel','=',$query['tapel']],
                ['semester','=', $query['semester']],
                ['rombel_id','=',$query['rombelId']],
                ['agama','=', $query['mapelId'] == 'pabp' ? $query['agama'] : null],
                ['tipe','=',$query['tipe']],
                ['mapel_id','=',$query['mapelId']]
            ])->get();

            return $nilais;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function ledger($request) {
        if ($request->user()->hasRole('guru_kelas')) {
            $rombel = Rombel::where('guru_id', $request->user()->userable->id)->whereIsActive(1)->first();
            $nilais = Nilai::where([
                                    ['tapel','=', $this->periode()['tapel']['kode']],
                                    ['semester','=', $this->periode()['tapel']['semester']],
                                    ['rombel_id','=', $rombel->kode]
                            ])
                            // ->select("")
                            // ->selectRaw("AVG(skor) AS rerata")
                            // ->groupBy('siswa_id','mapel_id')
                            ->get();
        }

        return $nilais;
    }

    private function periode() {
        return [
            'semester' => Semester::whereIsActive(1)->first(),
            'tapel' => Tapel::whereisActive(1)->first()
        ];
    }
}
