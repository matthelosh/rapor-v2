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
        // dd('tes');
        try {
            $guru = $request->user()->userable;

            $kaihsFilter = function ($query) use ($request) {
                if ($request->query("bulan") && $request->query("tahun")) {
                    $query
                        ->whereMonth("waktu", $request->query("bulan"))
                        ->whereYear("waktu", $request->query("tahun"));
                } else {
                    $query
                        ->whereYear("waktu", date("Y"))
                        ->whereMonth("waktu", date("m"));
                }
                $query->orderBy("kebiasaan");
            };
            $rombels = Rombel::whereGuruId($guru->id)
                ->where('tapel', Periode::tapel()->kode)
                ->with([
                    "wali_kelas",
                    "siswas" => function($query) use($kaihsFilter) {
                        $query->with(["kaihs" => $kaihsFilter]);
                    }
                    ])
                ->get();

            foreach($rombels as $rombel)
            {
                $rombel->siswas->each(function($siswa) use($request)
                {
                    $daftar_kebiasaan = [
                        "Bangun Pagi",
                        "Beribadah",
                        "Makan Sehat dan Bergizi",
                        "Gemar Belajar",
                        "Bermasyarakat",
                        "Tidur Cepat",
                        "Berolahraga",
                    ];

                    $grouped = $siswa->kaihs->groupBy("kebiasaan")->map->count();
                    $siswa->kebiasaan_count = collect(
                        $daftar_kebiasaan
                    )->mapWithKeys(function ($kebiasaan) use ($grouped) {
                        return [$kebiasaan => $grouped[$kebiasaan] ?? 0];
                    });
                });
            }
            return Inertia::render("Dash/Kaih/Home", [
                "rombels" => $rombels,
                "sekolahs" => \sekolahs($request->user()),
            ]);
        } catch (\Throwable $th) {
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
                "sekolahs" => \sekolahs($request->user()),
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
                "sekolahs" => \sekolahs($request->user()),
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
            DB::beginTransaction();

            $rombelId = $request->rombelId;
            $siswaId = $request->siswaId;
            $semester = Periode::semester()->kode;

            // 1. Build a lookup map from the request payload {'YYYY-MM-DD::Kebiasaan' => true}
            $requestPairs = [];
            foreach ($request->kegiatans as $kebiasaan => $tanggals) {
                foreach ($tanggals as $tanggal) {
                    // Normalize the key
                    $requestPairs[$tanggal . '::' . $kebiasaan] = true;
                }
            }

            // 2. Fetch all existing records for the student this semester
            $dbRecords = Kaih::where('rombel_id', $rombelId)
                ->where('siswa_id', $siswaId)
                ->where('semester', $semester)
                ->get();

            // 3. Determine which records to delete
            $idsToDelete = [];
            foreach ($dbRecords as $record) {
                $key = Carbon::parse($record->waktu)->format('Y-m-d') . '::' . $record->kebiasaan;
                if (!isset($requestPairs[$key])) {
                    $idsToDelete[] = $record->id;
                } else {
                    // This pair exists in both DB and request, so we don't need to act on it.
                    // Remove it from requestPairs to find out what's left to insert.
                    unset($requestPairs[$key]);
                }
            }

            // 4. Perform deletion
            if (!empty($idsToDelete)) {
                Kaih::whereIn('id', $idsToDelete)->delete();
            }

            // 5. Determine which records to create
            // $requestPairs now only contains pairs that are NOT in the DB.
            $dataToCreate = [];
            $now = Carbon::now("Asia/Jakarta");
            foreach (array_keys($requestPairs) as $key) {
                list($tanggal, $kebiasaan) = explode('::', $key, 2);
                $waktu = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $tanggal . " " . $now->format("H:i:s"),
                    "Asia/Jakarta"
                );
                $dataToCreate[] = [
                    "rombel_id" => $rombelId,
                    "siswa_id" => $siswaId,
                    "semester" => $semester,
                    "kebiasaan" => $kebiasaan,
                    "waktu" => $waktu->toDateTimeString(),
                    "is_done" => true,
                    "keterangan" => $request->keterangan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 6. Perform insertion
            if (!empty($dataToCreate)) {
                Kaih::insert($dataToCreate);
            }

            DB::commit();

            return back()->with(
                "message",
                "Rekap KAIH " . $siswaId . " disimpan."
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
