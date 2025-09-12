<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;
class AsesmenController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "Welcome to the Asesmen API",
        ]);
    }

    public function syncSekolah(Request $request)
    {
        try {
            $periode = \App\Models\Tapel::where("is_active", "1")->first();
            $sekolahs = Sekolah::with([
                "ks",
                "siswas",
                "siswas" => function ($siswa) use ($periode) {
                    $siswa
                        ->select(
                            "siswas.id",
                            "sekolah_id",
                            "nama",
                            "nisn",
                            "jk",
                            "tempat_lahir",
                            "tanggal_lahir",
                            "agama",
                            "foto",
                        )
                        ->whereHas("rombels", function ($rombelQuery) use (
                            $periode,
                        ) {
                            $rombelQuery->where("tapel", $periode->kode);
                        })
                        ->with([
                            "rombels" => function ($rombel) use ($periode) {
                                $rombel
                                    ->where("tapel", $periode->kode)
                                    ->select(
                                        "rombels.id",
                                        "kode",
                                        "label",
                                        "tingkat",
                                        "pararel",
                                    );
                            },
                        ]);
                },
                "gurus" => function ($guru) use ($periode) {
                    $guru
                        ->whereHas("rombels", function ($rombelQuery) use (
                            $periode,
                        ) {
                            $rombelQuery->where("tapel", $periode->kode);
                        })
                        ->with([
                            "rombels" => function ($rombel) use ($periode) {
                                $rombel
                                    ->where("tapel", $periode->kode)
                                    ->select(
                                        "rombels.id",
                                        "kode",
                                        "label",
                                        "tingkat",
                                        "pararel",
                                    );
                            },
                        ])
                        ->select(
                            "gurus.id",
                            "nama",
                            "gelar_belakang",
                            "nip",
                            "jabatan",
                            "pangkat",
                            "status",
                        );
                },
            ])
                ->where("kecamatan", "Wagir")
                ->get();
            return response()->json(
                [
                    "message" => "Sekolah synced successfully",
                    "sekolahs" => $sekolahs,
                ],
                200,
            );
            // Implement sync logic here
        } catch (\Exception $e) {
            return response()->json(
                [
                    "error" => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
