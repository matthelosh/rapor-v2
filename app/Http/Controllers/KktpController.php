<?php

namespace App\Http\Controllers;

use App\Models\Kktp;
use Illuminate\Http\Request;

class KktpController extends Controller
{
    public function store(Request $request)
    {
        try {
            $queries = $request->query();
            $datas = $request->datas;
            foreach ($datas as $data) {
                if ($data['minimal'] > 0) {
                    Kktp::updateOrCreate(
                        [
                            'id' => $data['id'] ?? null,
                            'rombel_id' => $queries['rombelId'],
                            'tapel' => $queries['tapel'],
                            'semester' => $queries['semester'],
                            'sekolah_id' => $queries['sekolahId'],
                            'mapel_id' => $data['mapel_id'],
                            'tingkat' => $queries['tingkat'],
                        ],
                        [
                            'minimal' => $data['minimal'],
                            'deskripsi' => 'null'
                        ]
                    );
                }
            }

            return back()->with('message', 'KKTP Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
