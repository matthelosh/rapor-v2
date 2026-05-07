<?php

namespace App\Http\Controllers;

use App\Models\Ortu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrtuController extends Controller
{

    public function impor(Request $request)
    {
        $datas = $request->datas;
        try {
            foreach ($datas as $data) {
                $ortus = [

                    'ayah' => [
                        'siswa_id' => $data['nisn'],
                        'nama' => $data['nama_ayah'],
                        'nik' => $data['nik_ayah'] ?? null,
                        'alamat' => $data['alamat_ayah'],
                        'relasi' => 'Ayah',
                        'hp' => $data['hp_ayah'] ?? null
                    ],
                    'ibu' => [
                        'siswa_id' => $data['nisn'],
                        'nama' => $data['nama_ibu'],
                        'nik' => $data['nik_ibu'] ?? null,
                        'alamat' => $data['alamat_ibu'],
                        'relasi' => 'Ibu',
                        'hp' => $data['hp_ibu'] ?? null
                    ],
                ];
                if (isset($data['nama_wali'])) {
                    $ortus['wali'] = [
                        'siswa_id' => $data['nisn'] ?? null,
                        'nama' => $data['nama_wali'] ?? null,
                        'nik' => $data['nik_wali'] ?? null,
                        'alamat' => $data['alamat_wali'] ?? null,
                        'relasi' => 'Wali',
                        'hp' => $data['hp_wali'] ?? null
                    ];
                }

                // }
                foreach ($ortus as $ortu) {
                    $newOrtu = new Ortu($ortu);
                    $newOrtu->save();
                }
                // dd($ayah, $ibu, $wali);
            }
            return back()->with('message', 'Data Ortu Diimpor');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->ortu);
            $ortu = $request->ortu;
            $ayah = $ortu['ayah'];
            $ibu = $ortu['ibu'];
            // $ayah['siswa_id'] = $ortu['siswa_id'];
            // $newAyah = new Ortu($ayah);
            // $newAyah->save();
            $simpanAyah = Ortu::updateOrCreate(
                [
                    'id' => $ayah['id'] ?? null
                ],
                [
                    'siswa_id' => $ortu['siswa_id'],
                    'nama' => $ayah['nama'],
                    'relasi' => $ayah['relasi'],
                    'alamat' => $ayah['alamat'] ?? '-',
                    'hp' => $ayah['hp'] ?? '-',
                    'pekerjaan' => $ayah['pekerjaan'] ?? ''
                ]
                );
            $simpanIbu = Ortu::updateOrCreate(
                [
                    'id' => $ibu['id'] ?? null
                ],
                [
                    'siswa_id' => $ortu['siswa_id'],
                    'nama' => $ibu['nama'],
                    'relasi' => $ibu['relasi'],
                    'alamat' => $ibu['alamat'] ?? '-',
                    'hp' => $ibu['hp'] ?? '-',
                    'pekerjaan' => $ibu['pekerjaan'] ?? ''
                ]
                );


            if ($ortu['wali']['nama']) {
                $wali = $ortu['wali'];

                $simpawali = Ortu::updateOrCreate(
                [
                    'id' => $wali['id'] ?? null
                ],
                [
                    'siswa_id' => $ortu['siswa_id'],
                    'nama' => $wali['nama'],
                    'relasi' => $wali['relasi'],
                    'alamat' => $wali['alamat'] ?? '-',
                    'hp' => $wali['hp'] ?? '-',
                    'pekerjaan' => $wali['pekerjaan'] ?? ''
                ]
                );
            }

            return \response()->json(['message' => 'Data Ortu disimpan']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function indexPekerjaan(Request $request)
    {
        try {
            $pekerjaans = DB::table('pekerjaans')->get();
            return \response()->json(['pekerjaans' => $pekerjaans]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
