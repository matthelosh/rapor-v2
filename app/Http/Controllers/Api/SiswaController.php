<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Helpers\Periode;
class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'sekolah_id' => 'required',
        ]);

        $siswas = Siswa::where('sekolah_id', $request->sekolah_id)
            ->where('status', 'aktif')
            ->with([
                'rombels' => function ($r) {
                    $r->where('tapel', Periode::tapel()->kode)->first();
                }
            ])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Siswa ditemukan',
            'data' => $siswas,
        ]);
    }
}
