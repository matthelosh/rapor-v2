<?php

namespace App\Http\Controllers;

use App\Events\JawabanReceived;
use App\Models\Guru;
use App\Models\Org;
use App\Models\Sekolah;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Helpers\Periode;

class DashboardController extends Controller
{
    // public function tesReverb()
    // {
    //     \event(new JawabanReceived("Ini Hanya Tes"));
    // }

    public function index(Request $request)
    {
        // \event(new JawabanReceived('Ini Jawaban'));
        $user = $request->user();
        $tapel = Tapel::whereIsActive(true)->pluck("kode")->first();
        $data = [];

        // Tes Dapodik
        // $response = Http::withOptions([
        //     'verify' => false,
        // ])
        //     ->withHeaders([
        //         'Authorization' => 'Bearer QteRgcGaC8TGojF',
        //         'Content-Type' => 'application/json'
        //     ])
        //     ->get('http://192.168.1.14:5774/WebService/getPengguna', ['npsn' => '20518848']);

        // dd($response);
        try {
            if ($request->user()->hasRole(["superadmin", "admin"])) {
                $data["sekolahs"] = Sekolah::with("ks", "gurus")
                    ->with([
                        "rombels" => function ($q) use ($tapel) {
                            $q->whereTapel($tapel);
                            // $q->with("siswas", function ($s) {
                            //     $s->where("status", "aktif");
                            // });
                        },
                        "siswas" => fn($s) => $s->whereStatus("aktif"),
                    ])
                    ->get();
                // $data["sekolahs"] = Sekolah::all();
            } elseif ($user->hasRole("ops")) {
                $data["sekolah"] = Sekolah::where(
                    "npsn",
                    $user->userable->sekolahs[0]->npsn
                )
                    ->with("gurus", "ks")
                    ->with("rombels", function ($q) use ($tapel) {
                        $q->where("tapel", $tapel);
                        $q->with("gurus", "wali_kelas");
                        $q->with(
                            "siswas",
                            fn($q) => $q->where("status", "aktif")
                        );
                    })
                    ->with("mapels")
                    ->first();
            } elseif ($user->hasRole("guru_kelas")) {
                $data["sekolah"] = Sekolah::where(
                    "npsn",
                    $user->userable->sekolahs[0]->npsn
                )
                    ->with("rombels", function ($r) {
                        $r->where("tapel", Periode::tapel()->kode);
                        $r->with("wali_kelas", "siswas");
                    })
                    ->with("mapels")
                    ->first();
            } elseif (
                $user->hasRole(["guru_agama", "guru_pjok", "guru_inggris"])
            ) {
                $guruId = $user->userable->id;
                $data["sekolahs"] = Sekolah::whereHas("gurus", function (
                    $q
                ) use ($guruId) {
                    $q->where("gurus.id", $guruId);
                })
                    ->with("rombels.siswas")
                    ->get();
            } elseif ($user->hasRole("org")) {
                // $data['org'] = Org::where
            }
            return Inertia::render("Dashboard", [
                "data" => $data,
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function operator(Request $request)
    {
        $user = $request->user();
        $ops = [];
        if ($user->hasRole("admin") || $user->hasRole("superadmin")) {
            $ops = Guru::whereHas("user.roles", function ($q) {
                $q->where("name", "ops");
            })
                ->with("user.roles")
                ->get();
        } else {
            $sekolah = Sekolah::where(
                "npsn",
                $user->userable->sekolahs[0]->npsn
            )->first();
            $ops = Guru::whereHas("user.roles", function ($q) {
                $q->where("name", "ops");
            })
                ->where("nip", $sekolah->npsn)
                ->with("user.roles")
                ->get();
        }

        return Inertia::render("Dash/Ops", [
            "ops" => $ops,
        ]);
    }
}
