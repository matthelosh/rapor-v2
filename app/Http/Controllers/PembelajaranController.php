<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Elemen;
use Inertia\Inertia;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\PembelajaranTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\Rombel;
use App\Helpers\Periode;
use App\Helpers\SekolahHelper;

class PembelajaranController extends Controller implements HasMiddleware
{
    use PembelajaranTrait;

    public function home(Request $request)
    {
        if ($request->user()->hasRole("admin_tp")) {
            // dd('Mapel');
            $permission_name = $request->user()->getPermissionNames();
            $permission = \explode("_", $permission_name[0]);
            $mapel = $permission[1];
            // dd($mapel);
            if ($mapel == "mapel") {
                // Guru Kelas
                if (
                    in_array(end($permission), [
                        "Islam",
                        "Kristen",
                        "Katolik",
                        "Hindu",
                        "Budha",
                        "Konghuchu",
                    ])
                ) {
                    $agama = end($permission);
                    $mapels = Mapel::where("kode", "pabp")
                        ->with([
                            "tps" => function ($t) use ($agama) {
                                $t->where("agama", $agama);
                            },
                        ])
                        ->get();
                } else {
                    $mapels = Mapel::where("kode", end($permission))->get();
                }
            } else {
                // Guru Kelas
                $mapels = Mapel::whereNot("kode", end($permission))
                    ->with([
                        "tps" => function ($t) use ($mapel) {
                            $t->where("tingkat", $mapel);
                        },
                    ])
                    ->get();
            }
        } elseif ($request->user()->hasRole(["admin", "superadmin", "ops"])) {
            $mapels = Mapel::with("tps")->get();
        } elseif ($request->user()->hasRole("guru_kelas")) {
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            $nip = $request->user()->userable->nip;
            $guruId = $request->user()->userable->id;
            $tingkat = Rombel::where("guru_id", $guruId)
                ->pluck("tingkat")
                ->toArray();
            /* dd($tingkat); */
            $mapels = Mapel::whereHas("sekolah", function ($s) use (
                $sekolahId
            ) {
                $s->where("npsn", $sekolahId);
            })
                ->with("tps", function ($t) use ($guruId, $tingkat) {
                    //$t->whereGuruId($guruId);
                    $t->where("semester", Periode::semester()->kode);
                    $t->whereIn("tingkat", $tingkat);
                })
                /* ->with('tps') */
                ->get();
            // dd($mapels);
        } else {
            /* dd("tes"); */
            $mapelId = $request->user()->hasRole("guru_agama")
                ? "pabp"
                : ($request->user()->hasRole("guru_pjok")
                    ? "pjok"
                    : "bing");
            // $agama = $mapelId == 'pabp' ? $request->user()->userable->agama : null;
            $guruId = $request->user()->userable->nip;
            $agamaGuru = $request->user()->userable->agama;
            $agamaQuery =
                $mapelId == "pabp"
                    ? ["agama", "=", $agamaGuru]
                    : ["agama", "=", null];
            $mapels = Mapel::whereKode($mapelId)
                ->with("tps", function ($t) use ($agamaQuery) {
                    $t->where("semester", Periode::semester()->kode);
                    $t->where([$agamaQuery]);
                })
                ->get();
        }
        return Inertia::render("Dash/Pembelajaran", [
            "mapels" => $mapels,
            "elemens" => Elemen::all(),
            "ekskuls" => Ekskul::all(),
            "sekolahs" => \App\Helpers\SekolahHelper::data($request->user()),
        ]);
    }

    public function assignMapel(Request $request)
    {
        // dd($request->sekolahId);
        try {
            $sekolah = Sekolah::findOrFail($request->sekolahId);
            // dd($request->mapels);
            $sekolah->mapels()->sync($request->mapels);

            return back()->with(
                "message",
                "Mapel ditambahkan ke " . $sekolah->nama
            );
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function imporMapel(Request $request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                Mapel::updateOrCreate(
                    [
                        "kode" => $data["kode"],
                    ],
                    [
                        "kode" => $data["kode"],
                        "label" => $data["label"],
                        "fase" => $data["fase"],
                        "kategori" => $data["kategori"],
                        "deskripsi" => $data["deskripsi"],
                    ]
                );
            }

            return back()->with("message", "Mapel Diimpor");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function imporEkskul(Request $request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                Ekskul::updateOrCreate(
                    [
                        "kode" => $data["kode"],
                    ],
                    [
                        "nama" => $data["nama"],
                        "keterangan" => $data["keterangan"],
                        "sifat" => $data["sifat"],
                        "is_active" => true,
                    ]
                );
            }

            return back()->with("message", "Ekskul Diimpor");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function assignEkskul(Request $request)
    {
        $sekolah = Sekolah::findOrFail($request->sekolahId);
        $sekolah->ekskuls()->sync($request->ekskuls);

        return back()->with(
            "message",
            "Ekskul ditambahkan ke " . $sekolah->nama
        );
    }

    public function indexEkskul(Request $request)
    {
        $sekolahId = $request->query("sekolahId");
        $ekskuls = Ekskul::whereHas("sekolah", function ($q) use ($sekolahId) {
            $q->where(function ($sq) use ($sekolahId) {
                $sq->where("npsn", $sekolahId);
            });
        })->get();

        return response()->json(["ekskuls" => $ekskuls]);
    }

    public static function middleware(): array
    {
        return [
            "role:admin|ops|guru_kelas|admin_tp|guru_agama|guru_pjok|guru_inggris",
        ];
    }
}
