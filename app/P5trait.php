<?php

namespace App;

use App\Models\NilaiP5;
use App\Models\Proyek;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Tapel;

trait P5trait
{
    public function getNilai($rombel_id, $proyek_id)
    {
        $nilais = NilaiP5::where('rombel_id', $rombel_id)
            ->where('proyek_id', $proyek_id)
            ->get();

        // if ($nilais->count() < 1) {
        $rombel = Rombel::whereKode($rombel_id)->with('siswas')->first();
        $proyek = Proyek::whereId($proyek_id)->with('apds')->first();
        $results = [];

        foreach ($rombel->siswas as $siswa) {
            $data = [
                'siswa_id' => $siswa->nisn,
                'nama' => $siswa->nama,
                'proyek_id' => $proyek->id,
                'rombel_id' => $rombel->kode,
                'nilais' => []
            ];
            foreach ($proyek->apds as $apd)
                array_push($data['nilais'], [
                    'apd_id' => $apd->id,
                    'skor' => NilaiP5::whereProyekId($proyek_id)->whereApdId($apd->id)->whereSiswaId($siswa->nisn)->value('nilai') ?? ''
                ]);

            array_push($results, $data);
        }
        // } else {
        //     $results = [];
        // }
        return $results;
    }

    public function store($datas): string
    {
        foreach ($datas as $data) {
            foreach ($data['nilais'] as $nilai) {
                NilaiP5::updateOrCreate(
                    [
                        'proyek_id' => $data['proyek_id'],
                        'siswa_id' => $data['siswa_id'],
                        'proyek_id' => $data['proyek_id'],
                        'rombel_id' => $data['rombel_id'],
                        'tapel' => $this->tapel()->kode,
                        'semester' => $this->semester()->kode,
                        'apd_id' => $nilai['apd_id'],
                    ],
                    [
                        'nilai' => $nilai['skor'] ?? 'BB',
                        'keterangan' => ''

                    ]
                );
            }
        }

        return "Nilai Disimpan";
    }



    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
    private function semester()
    {
        return Semester::whereIsActive(1)->first();
    }
}
