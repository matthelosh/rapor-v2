<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Helpers\Periode;
use Inertia\Inertia;
use App\Models\Kaih;
use DB;

class KaihController extends Controller
{
    public function home(Request $request)
    {
        try {
            $guru = $request->user()->userable;

            $rombels = Rombel::whereGuruId($guru->id)
                ->whereTapel(Periode::tapel()->kode)
                ->with(["siswas"])
                ->get();
            foreach ($rombels as $rombel) {
                $rombel->siswas->each(function ($siswa) use ($request) {
                    $siswa->load([
                        "kaihs" => function ($query) use ($request, $siswa) {
                            if (
                                $request->query("bulan") &&
                                $request->query("tahun")
                            ) {
                                $query
                                    ->whereMonth(
                                        "created_at",
                                        $request->query("bulan")
                                    )
                                    ->whereYear(
                                        "created_at",
                                        $request->query("tahun")
                                    );
                            } else {
                                $query
                                    ->whereYear("created_at", date("Y"))
                                    ->whereMonth("created_at", date("m"));
                            }
                            $query->orderBy("kebiasaan");
                        },
                    ]);
                    $daftar_kebiasaan = [
                        "Bangun Pagi",
                        "Beribadah",
                        "Makan Sehat dan Bergizi",
                        "Gemar Belajar",
                        "Bermasyarakat",
                        "Tidur Cepat",
                        "Berolahraga",
                    ];

                    $grouped = $siswa->kaihs
                        ->groupBy("kebiasaan")
                        ->map->count();

                    $siswa->kebiasaan_count = collect(
                        $daftar_kebiasaan
                    )->mapWithKeys(function ($kebiasaan) use ($grouped) {
                        return [$kebiasaan => $grouped[$kebiasaan] ?? 0];
                    });
                });
            }
            return Inertia::render("Dash/Kaih/Home", [
                "rombels" => $rombels,
            ]);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function perBulan(Request $request)
    {
        try {
            $kaihs = Kaih::where([
                ["rombel_id", "=", $request->query("rombelId")],
                ["siswa_id", "=", $request->query("siswaId")],
                ["bulan", "=", $request->query("bulan")],
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
