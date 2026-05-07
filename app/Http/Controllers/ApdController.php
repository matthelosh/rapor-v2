<?php

namespace App\Http\Controllers;

use App\Models\Apd;
use Illuminate\Http\Request;

class ApdController extends Controller
{
    public function impor(Request $request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                Apd::updateOrCreate(
                    [
                        'id' => $data['id'] ?? null,
                    ],
                    [
                        'elemen_id' => $data['elemen_id'],
                        'sub_elemen' => $data['sub_elemen'],
                        'teks' => $data['teks'],
                        'fase' => $data['fase'],
                        'tingkat' => $data['tingkat'] ?? \null,
                        'semester' => $data['semester'] ?? null,
                    ]
                );
            }
            return back()->with('message', 'Data APD disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
