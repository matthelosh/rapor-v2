<?php

namespace App\Http\Controllers;

use App\Models\Ortu;
use Illuminate\Http\Request;

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
}
