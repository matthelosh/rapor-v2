<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Periode;
use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Asesmen;
use App\Models\Rombel;
use App\Models\Tp;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getRombel(Request $request)
    {
        $npsn = $request->query('npsn');
        try {
            return response()->json([
                'rombels' => Rombel::where('sekolah_id', $npsn)->with('siswas')->get(),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getTp(Request $request)
    {
        $fase = $request->query('fase');
        $tingkat = $fase == 'A' ? ['1','2'] : ($fase == 'B' ? ['3','4'] : ['5','6']);

        $mapel = $request->query('mapel') ?? 'pabp';
        $agama = $request->query('agama') ?? 'Islam';
        $semester = $request->query('semester') ? ['semester','=', $request->query('semester')  ] : ['semester', 'LIKE', '%'];
        try {
            return response()->json([
                'tps' => Tp::where([

                    ['mapel_id', '=', $mapel],
                    ['agama', '=', $agama && $agama != 'null' ? $agama : null],
                    $semester
                ])->whereIn('tingkat', $tingkat)->get(['id', 'kode', 'teks', 'mapel_id', 'elemen']),
                'agama' => $agama == 'null'
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function storeNilai(Request $request)
    {
        $npsn = $request->query('npsn');
        try {
            return response()->json([
                'rombels' => Rombel::where('sekolah_id', $npsn)->with('siswas')->get(),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAsesmen(Request $request)
    {
        try {
            $jenjang = $request->query('jenjang');
            $tingkat = $request->query('tingkat');
            $mapel = $request->query('mapel');
            $agama = $request->query('agama');
            $semester = $request->query('semester');
            $npsn = $request->query('npsn');

            return \response()->json([
                'asesmens' => Asesmen::where([
                    // ['jenjang','=',$jenjang],
                    // ['tingkat','=',$jenjang],
                ])->get(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getKaldik()
    {
        try {
            $tapel = Periode::tapel();
            return Agenda::where('tapel', $tapel->kode)->where('tipe', 'libur')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
