<?php

namespace App\Http\Middleware;

use App\Helpers\Periode;
use App\Models\Gugus;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Pejabat;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = "app";

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // $user = $request->user()
        //     ? User::whereId($request->user()->id)
        //         ->with("userable")
        //         ->first()
        //     : null;
        $datas = [
            ...parent::share($request),
            "auth" => [
                "user" => fn() => $request
                    ->user()
                    ?->only(["id", "name", "email"]),
                "roles" => fn() => $request->user()?->roles->pluck("name"),
                "can" => fn() => $request
                    ->user()
                    ?->getAllPermissions()
                    ->pluck("name"),
                "userable" => fn() => $request->user()?->userable
            ],
            "flash" => [
                "message" => fn() => $request->session()->get("message"),
                "error" => fn() => $request->session()->get("error"),
            ],
            "pejabat" => Pejabat::first(),
            "periode" => [
                "tapel" => Periode::tapel(),
                "semester" => Periode::semester(),
            ],
            "app_env" => env("APP_ENV"),
            "appUrl" => env("APP_URL"),
            "appName" => env("APP_NAME"),
            // "guguses" => $user ? Gugus::all() : null,
            /* "mapels" => $user */
            /*     ? ($user->hasRole("admin") */
            /*         ? Mapel::all() */
            /*         : $this->mapels($user)) */
            /*     : null, */
        ];
        // if ($user) {
        //     $datas["sekolahs"] = $this->sekolahs($user);
        //     $datas["rombels"] = $user->hasRole(["admin", "superadmin", "org"])
        //         ? Rombel::whereTapel(Periode::tapel()->kode)->get()
        //         : // :
        //         ($user->hasRole("guru_kelas")
        //             ? Rombel::whereHas("wali_kelas", function ($w) use ($user) {
        //                 $w->where("guru_id", $user->userable->id);
        //             })
        //                 ->with("sekolah")
        //                 ->with("siswas", function ($q) {
        //                     $q->orderBy("nama", "ASC");
        //                 })
        //                 ->get()
        //             : ($user->hasRole("siswa")
        //                 ? Rombel::where(
        //                     "sekolah_id",
        //                     $user->userable->sekolah_id
        //                 )
        //                     ->whereHas("siswas")
        //                     ->get()
        //                 : Rombel::where(
        //                     "sekolah_id",
        //                     $user->userable->sekolahs[0]->npsn
        //                 )
        //                     ->with("siswas")
        //                     ->get()));

        //     if ($user->hasRole("siswa")) {
        //         // $datas['auth']['user']['userable'] = Siswa::where('id', $user->userable_id)->first();
        //         $datas["auth"]["user"] = User::whereId($user->id)
        //             ->with("userable")
        //             ->first();
        //     }

        //     if ($user->hasRole("ops")) {
        //         $datas["rombels"] = Rombel::where(
        //             "sekolah_id",
        //             $this->sekolahs($user)[0]->npsn
        //         )
        //             ->where("tapel", $this->periode()["tapel"]["kode"])
        //             ->get();
        //     }
        // }

        return $datas;
    }

    // private function mapels($user)
    // {
    //     $guru_mapels = ["guru_agama", "guru_pjok", "guru_inggris"];
    //     $mapels = $this->sekolahs($user)[0]->mapels;
    //     // $data['guguses'] = Gugus::all();
    //     // $data['mapels'] = $mapels;
    //     // dd($this->sekolahs($user)[0]->mapels->filter(fn($mapel) => $mapel->kode == 'pabp'));
    //     if ($user->hasRole("guru_kelas")) {
    //         $mapels = $mapels->filter(
    //             fn($mapel) => !\in_array($mapel->kode, ["pabp", "pjok", "bing"])
    //         );
    //         // dd($mapels);
    //         // $datas['rombels'] = Rombel::where('guru_id', $user->userable->id)->with('siswas.ortus', 'sekolah')->get();
    //     } elseif (in_array($user->getRoleNames()[0], $guru_mapels)) {
    //         if ($user->hasRole("guru_agama")) {
    //             // $data['mapels'] = $mapels->filter(fn($mapel) => $mapel->kode == 'pabp');
    //             $data["mapels"] = $mapels;
    //         } elseif ($user->hasRole("guru_pjok")) {
    //             $data["mapels"] = $mapels->filter(
    //                 fn($mapel) => $mapel->kode == "pjok"
    //             );
    //         } elseif ($user->hasRole("guru_inggris")) {
    //             $data["mapels"] = $mapels->filter(
    //                 fn($mapel) => $mapel->kode == "bing"
    //             );
    //         }
    //     }

    // return $mapels;
    // }
    private function sekolahs($user)
    {
        // if ($user->hasRole('admin') || $user->hasRole('superadmin') ) {
        $role = $user->getRoleNames()[0];
        $tapel = $this->periode()["tapel"]->kode;
        if (\in_array($role, ["superadmin", "admin", "admin_tp", "org"])) {
            return Sekolah::with(
                "mapels.tps",
                "ks",
                "ekskuls",
                "gugus",
                "siswas"
            )->get();
        } elseif ($role == "ops") {
            // dd($user->userable->sekolahs);
            return Sekolah::where("id", $user->userable->sekolahs[0]->id)
                ->with([
                    "mapels" => function ($q) {
                        $q->orderBy("id", "ASC")->with("tps");
                    },
                    "ks",
                    "ekskuls",
                    "gugus",
                ])
                ->get();
        } elseif ($role == "siswa") {
            return Sekolah::whereNpsn("20518848")->get();
        } else {
            // dd($user);
            return Sekolah::where("id", $user->userable->sekolahs[0]->id)
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
                    "ks",
                    "ekskuls",
                    "gugus",
                    // "rombels" => function ($r) use ($tapel) {
                    //     $r->where("tapel", $tapel);
                    //     $r->with("wali_kelas", "gurus");
                    // },
                ])
                ->get() ?? null;
        }
    }

    private function user($user)
    {
        $account = User::where("id", $user->id)
            ->with(["roles.permissions", "userable"])
            ->first();
        return $account;
    }

    //     return $mapels;
    // }
    // private function sekolahs($user)
    // {
    //     // if ($user->hasRole('admin') || $user->hasRole('superadmin') ) {
    //     $role = $user->getRoleNames()[0];
    //     $tapel = $this->periode()["tapel"]->kode;
    //     if (\in_array($role, ["superadmin", "admin", "admin_tp", "org"])) {
    //         return Sekolah::with(
    //             "mapels.tps",
    //             "ks",
    //             "ekskuls",
    //             "gugus",
    //             "siswas"
    //         )->get();
    //     } elseif ($role == "ops") {
    //         // dd($user->userable->sekolahs);
    //         return Sekolah::where("id", $user->userable->sekolahs[0]->id)
    //             ->with("mapels", function ($q) {
    //                 $q->orderBy("id", "ASC");
    //                 $q->with("tps");
    //             })
    //             /* ->with([ */
    //             /*     "rombels" => function ($r) use ($tapel) { */
    //             /*         $r->whereTapel($tapel); */
    //             /*         $r->with('wali_kelas', 'gurus'); */
    //             /*     }, */
    //             /* ]) */
    //             ->with("ks", "ekskuls", "gugus")
    //             ->get();
    //     } elseif ($role == "siswa") {
    //         return Sekolah::whereNpsn("20518848")->get();
    //     } else {
    //         // dd($user);
    //         return Sekolah::where("id", $user->userable->sekolahs[0]->id)
    //             ->with("ks", "ekskuls", "gugus")
    //             ->with([
    //                 "mapels" => function ($m) use ($role, $user) {
    //                     if ($role == "guru_kelas") {
    //                         $guruId = $user->userable->id;
    //                         $rombel = Rombel::whereHas("wali_kelas", function (
    //                             $w
    //                         ) use ($guruId) {
    //                             $w->where("id", $guruId);
    //                         })
    //                             ->where("tapel", Periode::tapel()->kode)
    //                             ->first();
    //                         $tingkat = $rombel ? $rombel->tingkat : "1";
    //                         $m->whereNotIn("kode", [
    //                             "pabp",
    //                             "pjok",
    //                             "bing",
    //                         ])->with("tps", function ($t) use ($tingkat) {
    //                             $t->where(
    //                                 "semester",
    //                                 Periode::semester()->kode
    //                             )->where("tingkat", $tingkat);
    //                         });
    //                     } elseif ($role == "guru_agama") {
    //                         $m->where("kode", "pabp")->with("tps", function (
    //                             $t
    //                         ) {
    //                             $t->where(
    //                                 "semester",
    //                                 Periode::semester()->kode
    //                             );
    //                         });
    //                     } elseif ($role == "guru_pjok") {
    //                         $m->where("kode", "pjok")->with("tps", function (
    //                             $t
    //                         ) {
    //                             $t->where(
    //                                 "semester",
    //                                 Periode::semester()->kode
    //                             );
    //                         });
    //                     } elseif ($role == "guru_inggris") {
    //                         $m->where("kode", "bing")->with("tps", function (
    //                             $t
    //                         ) {
    //                             $t->where(
    //                                 "semester",
    //                                 Periode::semester()->kode
    //                             );
    //                         });
    //                     }
    //                 },
    //                 // "rombels" => function ($r) use ($tapel) {
    //                 //     $r->where("tapel", $tapel);
    //                 //     $r->with("wali_kelas", "gurus");
    //                 // },
    //             ])
    //             ->get() ?? null;
    //     }
    // }

    // private function user($user)
    // {
    //     $account = User::where("id", $user->id)
    //         ->with("roles.permissions", "userable")
    //         ->first();
    //     return $account;
    // }

    // private function periode()
    // {
    //     $tapel = Tapel::whereIsActive(true)->first();
    //     $semester = Semester::whereisActive(true)->first();
    //     $periode = [
    //         "tapel" => $tapel,
    //         "semester" => $semester,
    //     ];
    //     return $tapel !== null ? $periode : null;
    // }
}
