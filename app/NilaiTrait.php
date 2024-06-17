<?php

namespace App;

use App\Models\Nilai;

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
                            'agama' => $query['agama'],
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
}
