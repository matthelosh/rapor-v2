<?php

use App\Models\Sekolah;
use App\Models\Rombel;
use App\Helpers\Periode;

if (!function_exists("sekolahs")) {
    function sekolahs($user)
    {
        // if ($user->hasRole('admin') || $user->hasRole('superadmin') ) {
        $role = $user->getRoleNames()[0];
        $tapel = Periode::tapel()->kode;
        if (\in_array($role, ["superadmin", "admin", "admin_tp", "org"])) {
            return Sekolah::with(
                "mapels.tps",
                "ks",
                "ekskuls",
            )->get();
        } elseif ($role == "ops") {
            // dd($user->userable->sekolahs);
            return Sekolah::where("id", $user->userable->sekolahs[0]->id)
                ->with("mapels", function ($q) {
                    $q->orderBy("id", "ASC");
                    $q->with("tps");
                })
                /* ->with([ */
                /*     "rombels" => function ($r) use ($tapel) { */
                /*         $r->whereTapel($tapel); */
                /*         $r->with('wali_kelas', 'gurus'); */
                /*     }, */
                /* ]) */
                ->with("ks", "ekskuls", "gugus")
                ->get();
        } elseif ($role == "siswa") {
            return Sekolah::whereNpsn("20518848")->get();
        } else {
            // dd($user);
            return Sekolah::where("id", $user->userable->sekolahs[0]->id)
                ->with("ks", "ekskuls", "gugus")
                ->with([
                    "mapels" => function ($m) use ($role, $user) {
                        if ($role == "guru_kelas") {
                            $guruId = $user->userable->id;
                            $rombel = Rombel::whereHas("wali_kelas", function (
                                $w
                            ) use ($guruId) {
                                $w->where("id", $guruId);
                            })
                                ->where("tapel", Periode::tapel()->kode)
                                ->first();
                            $tingkat = $rombel ? $rombel->tingkat : "1";
                            $m->whereNotIn("kode", [
                                "pabp",
                                "pjok",
                                "bing",
                            ])->with("tps", function ($t) use ($tingkat) {
                                $t->where(
                                    "semester",
                                    Periode::semester()->kode
                                )->where("tingkat", $tingkat);
                            });
                        } elseif ($role == "guru_agama") {
                            $m->where("kode", "pabp")->with("tps", function (
                                $t
                            ) {
                                $t->where(
                                    "semester",
                                    Periode::semester()->kode
                                );
                            });
                        } elseif ($role == "guru_pjok") {
                            $m->where("kode", "pjok")->with("tps", function (
                                $t
                            ) {
                                $t->where(
                                    "semester",
                                    Periode::semester()->kode
                                );
                            });
                        } elseif ($role == "guru_inggris") {
                            $m->where("kode", "bing")->with("tps", function (
                                $t
                            ) {
                                $t->where(
                                    "semester",
                                    Periode::semester()->kode
                                );
                            });
                        }
                    },
                    // "rombels" => function ($r) use ($tapel) {
                    //     $r->where("tapel", $tapel);
                    //     $r->with("wali_kelas", "gurus");
                    // },
                ])
                ->get() ?? null;
        }
    }
}
