<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Helpers\Periode;
use Inertia\Inertia;
use App\Models\Kaih;

class KaihController extends Controller
{
    public function home(Request $request)
    {
        try {
            $guru = $request->user()->userable;
            $rombels = Rombel::whereGuruId($guru->id)
                ->whereTapel(Periode::tapel()->kode)
                ->with("siswas")
                ->get();
            $rombels->each(function ($rombel) {
                $rombel->siswas->each(function ($siswa) {
                    $siswa->kaihs = [
                        "Bangun Pagi" => 30,
                        "Beribadah" => 60,
                        "Berolahraga" => 50,
                        "Makan Sehat dan Bergizi" => 70,
                        "Gemar Belajar" => 25,
                        "Bermasyarakat" => 80,
                        "Tidur Cepat" => 40,
                    ];
                });
            });
            // dd($rombels);
            return Inertia::render("Dash/Kaih/Home", [
                "rombels" => $rombels,
            ]);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function rekapSiswa(Request $request)
    {
        try {
            $kaihs = Kaih::where([
                ["rombel_id", "=", $request->query("rombelId")],
                ["siswa_id", "=", $request->query("siswaId")],
                ["semester", "=", $request->query("semester")],
            ])->get();

            return response()->json([
                "success" => true,
                "kaihs" => $kaihs,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Error: " . $th->getMessage(),
            ]);
        }
    }
}
