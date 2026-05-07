<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $queries = $request->query();
        try {
            $absen = Absensi::where([
                ['siswa_id', '=', $queries['siswaId']],
                ['tapel', '=', $queries['tapel']],
                ['semester', '=', $queries['semester']]
            ])->first();
            return response()->json(['absen' => $absen]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        $queries = $request->query();
        $absen = $request->absen;
        try {
            Absensi::updateOrCreate(
                [
                    // 'id' => $request['id'] ?? null,
                    'siswa_id' => $queries['siswaId'],
                    'tapel' => $queries['tapel'],
                    'semester' => $queries['semester'],
                    'rombel_id' => $queries['rombelId']
                ],
                [
                    'ijin' => $absen['ijin'] ?? 0,
                    'sakit' => $absen['sakit'] ?? 0,
                    'alpa' => $absen['alpa'] ?? 0
                ]
            );
            return back()->with('message', 'Absen siswa disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
