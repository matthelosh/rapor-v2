<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Guru;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use App\Services\AJenjangService;
use App\Models\Sekolah;
use App\Helpers\SekolahHelper;

class AdministrasiController extends Controller
{
    public function presensiGuru(Request $request)
    {
        try {
            $month = $request->query("bulan") ?? date("m");
            $tahun = $request->query("tahun") ?? date("Y");
            $nip = $request->user()->userable->nip;
            $npsn =
                $request->query("sekolahId") ??
                $request->user()->userable->sekolahs[0]->npsn;

            $agendas = Agenda::whereMonth("mulai", $month)
                ->orWhereMonth("selesai", $month)
                ->whereYear("mulai", $tahun)
                ->orWhereYear("selesai", $tahun)
                ->where("tipe", "libur")
                ->orderBy("mulai", "ASC")
                ->get();

            $events = $agendas
                ->flatMap(function ($agenda) use ($month, $tahun) {
                    $start = Carbon::parse($agenda->mulai);
                    $end = Carbon::parse($agenda->selesai);

                    $dates = [];
                    while ($start->lte($end)) {
                        if ($start->month == $month && $start->year == $tahun) {
                            $dates[] = [
                                "nama" => $agenda->nama,
                                "tipe" => $agenda->tipe,
                                "tanggal" => $start->toDateString(),
                            ];
                        }

                        $start->addDay();
                    }

                    return $dates;
                })
                ->unique()
                ->values()
                ->all();

            $liburMap = [];
            foreach ($events as $event) {
                $liburMap[$event["tanggal"]] = $event;
            }

            $bulan = Carbon::create($tahun, $month, 1);
            $start = $bulan
                ->copy()
                ->startOfMonth()
                ->startOfWeek(Carbon::MONDAY);
            $end = $bulan->copy()->endOfMonth()->endOfWeek(Carbon::MONDAY);

            $period = CarbonPeriod::create($start, $end);
            $mingguKe = 1;
            $hasil = [];
            $mingguSekarang = [];

            foreach ($period as $tanggal) {
                $isInMonth = $tanggal->month === $bulan->month;
                $tanggalStr = $tanggal->toDateString();

                $isLibur = false;
                $namaLibur = null;
                if (isset($liburMap[$tanggalStr])) {
                    $namaLibur = $liburMap[$tanggalStr]["nama"];
                    $isLibur = true;
                }
                if ($tanggal->translatedFormat("l") !== "Minggu") {
                    $mingguSekarang[] = [
                        "hari" => $tanggal->translatedFormat("l"),
                        "tanggal" => $isInMonth ? $tanggalStr : null,
                        "isLibur" => $isLibur,
                        "namaLibur" => $namaLibur,
                    ];
                }

                if (count($mingguSekarang) === 6) {
                    $hasil[] = [
                        "minggu_ke" => $mingguKe,
                        "tanggals" => $mingguSekarang,
                    ];
                    $mingguKe++;
                    $mingguSekarang = [];
                }
            }

            // $sekolahs = Sekolah::whereHas("gurus", function ($g) use ($nip) {
            //     $g->where("nip", $nip);
            // })
            //     ->with("ks")
            //     ->get();
            return Inertia::render("Dash/Administrasi/PresensiGuru", [
                "agendas" => $agendas,
                "gurus" => Guru::whereHas("sekolahs", function ($s) use (
                    $npsn
                ) {
                    $s->where("npsn", $npsn);
                })
                    ->whereNot("jabatan", "Ops")
                    ->get(),
                "weeks" => $hasil,
                "sekolahs" => SekolahHelper::data($request->user()),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function homeAkhirJenjang(
        Request $request,
        AJenjangService $jenjangService
    ) {
        try {
            $jenjangs = $jenjangService->home();
            return Inertia::render("Dash/Administrasi/AkhirJenjang", [
                "rombels" => $jenjangs["rombels"],
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
