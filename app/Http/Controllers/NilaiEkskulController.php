<?php

namespace App\Http\Controllers;

use App\Models\NilaiEkskul;
use Illuminate\Http\Request;

class NilaiEkskulController extends Controller
{
    public function index(Request $request)
    {
        $queries = $request->query();
        try {
            $nilais = NilaiEkskul::where([
                ["siswa_id", "=", $queries["siswaId"]],
                ["tapel", "=", $queries["tapel"]],
                ["semester", "=", $queries["semester"]],
            ])->get();
            return response()->json(["nilais" => $nilais]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function store(Request $request)
    {
        try {
            $queries = $request->query();
            $nilais = $request->nilais;
            foreach ($nilais as $nilai) {
                if ($nilai["nilai"] !== null) {
                    NilaiEkskul::updateOrCreate(
                        [
                            // 'id' => $request['id'] ?? null,
                            "siswa_id" => $queries["siswaId"],
                            "tapel" => $queries["tapel"],
                            "semester" => $queries["semester"],
                            "ekskul_id" => $nilai["ekskulId"],
                        ],
                        [
                            "nilai" => $nilai["nilai"],
                            "deskripsi" => $nilai["deskripsi"],
                        ]
                    );
                }
            }
            return back()->with("message", "Nilai EKksul Disimpan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            NilaiEkskul::destroy($id);
            return back()->with("message", "Nilai EKskul Dihapus");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
