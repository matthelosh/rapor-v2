<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $queries = $request->query();
        try {
            $catatan = Catatan::where([
                ['siswa_id', '=', $queries['siswaId']],
                ['tapel', '=', $queries['tapel']],
                ['semester', '=', $queries['semester']]
            ])->first();
            return response()->json(['catatan' => $catatan]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function store(Request $request)
    {
        $queries = $request->query();
        $catatan = $request->catatan;
        try {
            Catatan::updateOrCreate(
                [
                    // 'id' => $request['id'] ?? null,
                    'siswa_id' => $queries['siswaId'],
                    'tapel' => $queries['tapel'],
                    'semester' => $queries['semester'],
                    'rombel_id' => $queries['rombelId']
                ],
                [
                    'teks' => $catatan,
                ]
            );
            return back()->with('message', 'Catatan siswa disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
