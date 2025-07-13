<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Helpers\Periode;
use Inertia\Inertia;
use App\Models\Kaih;
use DB;
use Carbon\Carbon;
use App\Helpers\SekolahHelper;

class KaihController extends Controller
{
    public function home(Request $request)
    {
        try {
            $guru = $request->user()->userable;

            $rombels = Rombel::whereGuruId($guru->id)
                ->whereTapel(Periode::tapel()->kode)
                ->with(["siswas", "wali_kelas"])
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
                                        "waktu",
                                        $request->query("bulan")
                                    )
                                    ->whereYear(
                                        "waktu",
                                        $request->query("tahun")
                                    );
                            } else {
                                $query
                                    ->whereYear("waktu", date("Y"))
                                    ->whereMonth("waktu", date("m"));
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
                "sekolahs" => SekolahHelper::data($request->user()),
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
                "sekolahs" => SekolahHelper::data($request->user()),
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
            $startDate =
                Periode::semester()->kode == "1"
                    ? Carbon::parse(
                        explode("/", Periode::tapel()->label)[0] . "-07-01"
                    )->startOfDay()
                    : Carbon::parse(
                        explode("/", Periode::tapel()->label)[1] . "-01-01"
                    )->startOfDay();
            $endDate =
                Periode::semester()->kode == "1"
                    ? Carbon::parse(
                        explode("/", Periode::tapel()->label)[0] . "-12-31"
                    )->endOfDay()
                    : Carbon::parse(
                        explode("/", Periode::tapel()->label)[1] . "-06-30"
                    )->endOfDay();
            $kaihs = Kaih::where([
                ["rombel_id", "=", $request->query("rombelId")],
                ["siswa_id", "=", $request->query("siswaId")],
                ["semester", "=", $request->query("semester")],
            ])
                ->whereBetween("waktu", [$startDate, $endDate])
                ->get();

            $daftar_kebiasaan = [
                "Bangun Pagi",
                "Beribadah",
                "Makan Sehat dan Bergizi",
                "Gemar Belajar",
                "Bermasyarakat",
                "Tidur Cepat",
                "Berolahraga",
            ];

            $grouped = $kaihs->groupBy("kebiasaan")->map->count();
            // $rekap = [];
            $rekap = collect($daftar_kebiasaan)->mapWithKeys(function (
                $kebiasaan
            ) use ($grouped) {
                return [$kebiasaan => $grouped[$kebiasaan] ?? 0];
            });

            // dd($kaihs);
            return response()->json([
                "success" => true,
                "rekap" => $rekap,
                "sekolahs" => SekolahHelper::data($request->user()),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Error: " . $th->getMessage(),
            ]);
        }
    }

    public function inputRekap(Request $request)
    {
        try {
            // dd($request->all());
            $tgls = [];
            foreach ($request->kegiatans as $k => $tanggals) {
                foreach ($tanggals as $tanggal) {
                    $now = Carbon::now("Asia/Jakarta");
                    $waktu = Carbon::createFromFormat(
                        "Y-m-d H:i:s",
                        $tanggal . " " . $now->format("H:i:s"),
                        "Asia/Jakarta"
                    );
                    $splitted_tanggal = explode("-", $tanggal);
                    $exists = Kaih::whereYear("waktu", $splitted_tanggal[0])
                        ->whereMonth("waktu", $splitted_tanggal[1])
                        ->whereDate("waktu", $splitted_tanggal[2])
                        ->get();
                    if ($exists->count() < 1) {
                        Kaih::create([
                            "rombel_id" => $request->rombelId,
                            "siswa_id" => $request->siswaId,
                            "semester" => Periode::semester()->kode,
                            "kebiasaan" => $k,
                            "waktu" => $waktu->toDateTimeString(),
                            "is_done" => true,
                            "keterangan" => $request->keterangan,
                        ]);
                    }
                    // array_push($tgls, $tanggal);
                }
            }
            // dd($tgls);
            return back()->with(
                "message",
                "Rekap KAIH " . $request->siswaId . " disimpan."
            );
        } catch (\THrowable $th) {
            dd($th);
        }
    }
}
