<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::prefix("")->group(function () {
    Route::get("/", [FrontController::class, "home"])->name("home");

    Route::get("/tes-dapodik", [FrontController::class, "tesdapodik"]);
    Route::get("/tes-s3", function (Request $request) {
        dd(Storage::disk("s3")->allFiles());
    });
    Route::get("/baca/{slug}", [PostController::class, "read"])->name(
        "home.post.read"
    );

    Route::get("/cari", [FrontController::class, "cari"])->name(
        "front.post.search"
    );

    Route::get("/berita", [FrontController::class, "berita"])->name(
        "front.berita"
    );
    Route::get("/info", [FrontController::class, "info"])->name("front.info");
    Route::get("/galeri", [FrontController::class, "galeri"])->name(
        "front.galeri"
    );
    Route::get("/agenda", [FrontController::class, "agenda"])->name(
        "front.agenda"
    );
    Route::get("/agenda/{id}/daftar", [
        AgendaController::class,
        "daftar",
    ])->name("front.agenda.daftar");

    // Workshop
    Route::prefix("workshop")->group(function () {
        Route::get("/", [WorkshopController::class, "home"])->name("workshop");
    });

    Route::prefix("sertifikat")->group(function () {
        Route::get("/", [SertifikatController::class, "home"])->name(
            "sertifikat.front"
        );
        Route::get("/cetak", [SertifikatController::class, "cetak"])->name(
            "sertifikat.cetak"
        );
        Route::get("/verifikasi", [
            SertifikatController::class,
            "verify",
        ])->name("sertifikat.verify");
    });

    Route::prefix("verifikasi")->group(function () {
        Route::prefix("transkrip")->group(function () {
            Route::get("{nisn}", [
                VerifyController::class,
                "verifyTranskrip",
            ])->name("verifikasi.transkrip");
        });
        Route::prefix("rapor")->group(function () {
            Route::post("/permanen", [
                RaporController::class,
                "makePermanent",
            ])->name("dashboard.rapor.permanen");
        });
    });

    // Captcha
    Route::prefix("/captcha")->group(function () {
        Route::get("/new", [CaptchaController::class, "new"])->name(
            "captcha.new"
        );
    });
});

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );

    Route::get("/userdetail", function (Request $request) {
        return response()->json(["userdetail" => $request->user()->userable]);
    });

    Route::prefix("dashboard")->group(function () {
        Route::post("/tes-reverb", [
            DashboardController::class,
            "tesReverb",
        ])->name("dashboard.home.tes");

        Route::middleware("role:ops")
            ->prefix("bukuinduk")
            ->group(function () {
                Route::get("/", [BukuindukController::class, "home"])->name(
                    "dashboard.bukuinduk.home"
                );
            });
        Route::get("/", [DashboardController::class, "index"])
            ->middleware(["auth", "verified"])
            ->name("dashboard");
        // Gugus
        Route::prefix("gugus")->group(function () {
            Route::get("/", [GugusController::class, "home"])->name(
                "dashboard.gugus"
            );
            Route::post("/store", [GugusController::class, "store"])->name(
                "dashboard.gugus.store"
            );
            Route::delete("/{id}", [GugusController::class, "destroy"])->name(
                "dashboard.gugus.destroy"
            );
        });

        Route::prefix("operator")->group(function () {
            Route::get("/", [DashboardController::class, "operator"])
                ->middleware(["role:admin|ops|superadmin"])
                ->name("dashboard.operator");
        });
        Route::prefix("sekolah")->group(function () {
            Route::get("/", [SekolahController::class, "home"])
                ->middleware(["role:admin|ops|superadmin"])
                ->name("dashboard.sekolah");
            Route::post("/index", [SekolahController::class, "index"])->name(
                "dashboard.sekolah.index"
            );
            Route::post("/", [SekolahController::class, "store"])->name(
                "dashboard.sekolah.store"
            );
            Route::put("/", [SekolahController::class, "update"])
                ->middleware(["role:admin|ops"])
                ->name("dashboard.sekolah.update");
            Route::post("/impor", [SekolahController::class, "impor"])->name(
                "dashboard.sekolah.impor"
            );
            Route::delete("/{id}", [SekolahController::class, "destroy"])
                ->middleware(["role:admin"])
                ->name("dashboard.sekolah.destroy");
            Route::post("/{id}/operator", [SekolahController::class, "addOps"])
                ->middleware(["role:admin"])
                ->name("dashboard.sekolah.ops.add");
        });

        Route::prefix("guru")->group(function () {
            Route::get("/", [GuruController::class, "index"])
                ->name("dashboard.guru")
                ->name("can:read_guru");
            Route::post("/", [GuruController::class, "store"])
                ->name("dashboard.guru.store")
                ->middleware(["role:admin|ops"]);
            Route::post("/get", [GuruController::class, "show"])->name(
                "dashboard.guru.show"
            );
            Route::put("/", [GuruController::class, "update"])->name(
                "dashboard.guru.update"
            );
            Route::post("/impor", [GuruController::class, "impor"])->name(
                "dashboard.guru.impor"
            );
            Route::post("/account/add", [GuruController::class, "addAccount"])
                ->name("dashboard.guru.account.add")
                ->middleware(["role:admin|ops"]);
            Route::delete("/{id}", [GuruController::class, "destroy"])
                ->name("dashboard.guru.destroy")
                ->middleware(["role:admin|ops"]);
        });

        Route::prefix("siswa")->group(function () {
            Route::get("/", [SiswaController::class, "home"])
                ->name("dashboard.siswa")
                ->middleware("can:read_siswa");
            Route::post("/", [SiswaController::class, "store"])->name(
                "dashboard.siswa.store"
            );
            
            Route::post("/account/add", [
                SiswaController::class,
                "addAccount",
            ])->name("dashboard.siswa.account.add");
            Route::post("/account/bulk/add", [
                SiswaController::class,
                "addBulkAccount",
            ])->name("dashboard.siswa.account.bulk.add");
            Route::post("/nonmember", [
                SiswaController::class,
                "nonMember",
            ])->name("dashboard.siswa.nonmember");
            Route::put("/", [SiswaController::class, "update"])->name(
                "dashboard.siswa.update"
            );
            Route::post("/impor", [SiswaController::class, "impor"])->name(
                "dashboard.siswa.impor"
            );
            Route::post('/lulus', [SiswaController::class, 'luluskan'])->name('dashboard.siswa.lulus');
            Route::post("/cari/{nisn}", [SiswaController::class, 'cariSiswa'])->name('dashboard.siswa.cari');
            Route::delete("/{id}", [SiswaController::class, "destroy"])->name(
                "dashboard.siswa.destroy"
            );
            Route::prefix("ortu")->group(function () {
                Route::post("/", [OrtuController::class, "store"])->name(
                    "dashboard.siswa.ortu.store"
                );
                Route::post("/impor", [OrtuController::class, "impor"])->name(
                    "dashboard.siswa.ortu.impor"
                );
                Route::get("/pekerjaan", [
                    OrtuController::class,
                    "indexPekerjaan",
                ])->name("dashboard.siswa.ortu.pekerjaan.index");
            });

            Route::post("/impor/dapodik", [
                SiswaController::class,
                "imporDapodik",
            ])->name("dashboard.siswa.impor.dapodik");
        });

        Route::prefix("rombel")->group(function () {
            Route::get("/", [RombelController::class, "home"])
                ->name("dashboard.rombel")
                ->middleware("can:read_rombel");
            Route::get("/index", [RombelController::class, "index"])->name(
                "dashboard.rombel.index"
            );
            Route::post("/", [RombelController::class, "store"])->name(
                "dashboard.rombel.store"
            );
            Route::get("/{kode}/{tingkat}", [
                RombelController::class,
                "show",
            ])->name("dashboard.rombel.show");
            Route::post("/member/attach", [
                RombelController::class,
                "attachMember",
            ])->name("dashboard.rombel.member.attach");
            Route::post("/member/detach", [
                RombelController::class,
                "detachMember",
            ])->name("dashboard.rombel.member.detach");
            Route::put("/", [RombelController::class, "update"])->name(
                "dashboard.rombel.update"
            );
            Route::delete("/{id}", [RombelController::class, "destroy"])->name(
                "dashboard.rombel.destroy"
            );

            Route::prefix("kktp")->group(function () {
                Route::post("/store", [KktpController::class, "store"])->name(
                    "dashboard.rombel.kktp.store"
                );
            });
        });

        Route::prefix("pembelajaran")->group(function () {
            Route::get("/", [PembelajaranController::class, "home"])->name(
                "dashboard.pembelajaran"
            );
            Route::post("/elemen/impor", [
                ElemenController::class,
                "impor",
            ])->name("dashboard.pembelajaran.elemen.impor");
            Route::post('/mapel/store', [MapelController::class, 'store'])->name('dashboard.mapel.store');
            Route::post("/mapel/assign", [
                PembelajaranController::class,
                "assignMapel",
            ])->name("dashboard.pembelajaran.mapel.assign");
            Route::post("/mapel/impor", [
                PembelajaranController::class,
                "imporMapel",
            ])->name("dashboard.pembelajaran.mapel.impor");
            Route::delete('/mapel/{id}', [MapelController::class, 'destroy'])->name('dashboard.mapel.destroy');
            Route::prefix("cp")->group(function () {
                Route::post("/store", [CpController::class, "store"])->name(
                    "dashboard.cp.store"
                );
            });

            Route::prefix("tp")->group(function () {
                Route::post("/", [TpController::class, "index"])->name(
                    "dashboard.pembelajaran.tp.index"
                );
                Route::post("/impor", [TpController::class, "impor"])->name(
                    "dashboard.pembelajaran.tp.impor"
                );
                Route::post("/store", [TpController::class, "store"])->name(
                    "dashboard.pembelajaran.tp.store"
                );
                Route::delete("/{id}", [TpController::class, "destroy"])->name(
                    "dashboard.pembelajaran.tp.destroy"
                );
            });

            Route::prefix("ekskul")->group(function () {
                Route::get("/", [
                    PembelajaranController::class,
                    "indexEkskul",
                ])->name("dashboard.pembelajaran.ekskul");
                Route::post('/store', [PembelajaranController::class, 'storeEkskul'])->name('dashboard.pembelajaran.ekskul.store');
                Route::delete('/{id}', [PembelajaranController::class, 'destroyEkskul'])->name('dashboard.pembelajaran.ekskul.destroy');
                Route::post("/impor", [
                    PembelajaranController::class,
                    "imporEkskul",
                ])->name("dashboard.pembelajaran.ekskul.impor");
                Route::post("/assign", [
                    PembelajaranController::class,
                    "assignEkskul",
                ])->name("dashboard.pembelajaran.ekskul.assign");
            });
        });

        Route::prefix("post")->group(function () {
            Route::get("/", [PostController::class, "home"])->name(
                "dashboard.post.home"
            );
            Route::post("/store", [PostController::class, "store"])->name(
                "dashboard.post.store"
            );
            Route::put("/{id}", [PostController::class, "update"])->name(
                "dashboard.post.update"
            );
            Route::delete("/{id}", [PostController::class, "destroy"])->name(
                "dashboard.post.destroy"
            );
            Route::post("/image/upload", [
                PostController::class,
                "uploadImage",
            ])->name("dashboard.post.image.upload");
            Route::post("/image/list", [
                PostController::class,
                "listFiles",
            ])->name("dashboard.post.image.list");
        });

        Route::prefix("asesmen")->group(function () {
            Route::get("/", [AsesmenController::class, "home"])
                ->middleware([
                    "role:superadmin|admin|guru_kelas|guru_pjok|guru_agama|guru_inggris|ops",
                    "can:read_asesmen",
                ])
                ->name("dashboard.asesmen");
            Route::post("/store", [AsesmenController::class, "store"])
                ->middleware("can:add_asesmen")
                ->name("dashboard.asesmen.store");
            Route::post("/monitor/reload", [
                AsesmenController::class,
                "reloadASesmen",
            ])->name("dashboard.asesmen.monitor.reload");

            Route::post("/attach/{id}", [
                AsesmenController::class,
                "attachSoal",
            ])
                ->middleware("can:update_asesmen")
                ->name("dashboard.asesmen.soal.attach");
            Route::post("/detach/{id}", [
                AsesmenController::class,
                "detachSoal",
            ])
                ->middleware("can:update_asesmen")
                ->name("dashboard.asesmen.soal.detach");

            Route::post("/{kode}/kunci/store", [
                KunciJawabanController::class,
                "store",
            ])->name("dashboard.asesmen.kunci.store");

            Route::put("/{id}", [AsesmenController::class, "update"])
                ->middleware("can:update_asesmen")
                ->name("dashboard.asesmen.update");
            Route::delete("/{id}", [AsesmenController::class, "destroy"])
                ->middleware("can:delete_asesmen")
                ->name("dashboard.asesmen.destroy");
        });

        Route::prefix("analisis")
            ->group(function () {
                Route::get("/", [AnalisisController::class, "home"])->name(
                    "dashboard.analisis.home"
                );
                Route::post("/store", [
                    AnalisisController::class,
                    "store",
                ])->name("dashboard.analisis.store");
                Route::post("/cek-jawaban", [
                    AnalisisController::class,
                    "cekJawaban",
                ])->name("dashboard.analisis.cek-jawaban");
            })
            ->middleware("role:guru_kelas|guru_agama|guru_inggris|guru_pjok");

        Route::prefix("soal")->group(function () {
            Route::get("/", [SoalController::class, "home"])
                ->middleware("can:read_soal")
                ->name("dashboard.soal");
            Route::get("/all", [SoalController::class, "allSoal"])
                ->middleware("can:read_soal")
                ->name("dashboard.soal.all");
            Route::post("/image/upload", [
                SoalController::class,
                "uploadImage",
            ])->name("dashboard.soal.image.upload");
            Route::post("/store", [SoalController::class, "store"])
                ->middleware("can:add_soal")
                ->name("dashboard.soal.store");
            Route::delete("/{id}", [SoalController::class, "destroy"])->name(
                "dashboard.soal.destroy"
            );
        });

        Route::prefix("nilai")->group(function () {
            Route::get("/", [NilaiController::class, "home"])
                ->name("dashboard.nilai")
                ->middleware([
                    "role:guru_kelas|guru_agama|guru_pjok|guru_inggris",
                ]);
            Route::post("/", [NilaiController::class, "index"])
                ->name("dashboard.nilai.index")
                ->middleware([
                    "role:guru_kelas|guru_agama|guru_pjok|guru_inggris",
                ]);
            Route::post("/store", [NilaiController::class, "store"])
                ->name("dashboard.nilai.store")
                ->middleware([
                    "role:guru_kelas|guru_agama|guru_pjok|guru_inggris",
                ]);

            Route::prefix("ekskul")->group(function () {
                Route::get("/", [NilaiEkskulController::class, "index"])->name(
                    "dashboard.nilai.ekskul.index"
                );
                Route::post("/store", [
                    NilaiEkskulController::class,
                    "store",
                ])->name("dashboard.nilai.ekskul.store");
                Route::delete("/{id}", [
                    NilaiEkskulController::class,
                    "destroy",
                ])->name("dashboard.nilai.ekskul.destroy");
            });

            Route::prefix("absen")->group(function () {
                Route::get("/", [AbsensiController::class, "index"])->name(
                    "dashboard.nilai.absen.index"
                );
                Route::post("/store", [
                    AbsensiController::class,
                    "store",
                ])->name("dashboard.nilai.absen.store");
            });
            Route::prefix("catatan")->group(function () {
                Route::get("/", [CatatanController::class, "index"])->name(
                    "dashboard.nilai.catatan.index"
                );
                Route::post("/store", [
                    CatatanController::class,
                    "store",
                ])->name("dashboard.nilai.catatan.store");
            });

            Route::post('/{rombelId}/{mapelId}/{jenis}', [
                NilaiController::class,
                "bulkDelete",
            ])->name("dashboard.nilai.hapus.bulk");
        });

        Route::prefix("ledger")->group(function () {
            Route::get("/", [LedgerController::class, "home"])
                ->name("dashboard.ledger")
                ->middleware("role:guru_kelas");
            Route::post("/", [LedgerController::class, "index"])
                ->name("dashboard.ledger.index")
                ->middleware("role:guru_kelas");
        });

        Route::prefix("rapor")->group(function () {
            Route::get("/labelnama", [RaporController::class, "labelNama"])
                ->name("dashboard.rapor.labelnama")
                ->middleware(["role:guru_kelas"]);
            Route::get("/cetak", [RaporController::class, "home"])
                ->name("dashboard.rapor.cetak")
                ->middleware(["role:guru_kelas"]);
            Route::prefix("periodik")->group(function () {
                Route::get("/", [RaporController::class, "periodik"])
                    ->name("dashboard.rapor.periodik")
                    ->middleware(["role:guru_kelas"]);
            });
            Route::post("/pts", [RaporController::class, "raporPTS"])
                ->name("dashboard.rapor.pts")
                ->middleware(["role:guru_kelas"]);
            Route::post("/pas", [RaporController::class, "raporPAS"])
                ->name("dashboard.rapor.pas")
                ->middleware(["role:guru_kelas|ops"]);
            Route::prefix("tanggal")->group(function () {
                Route::get("/", [RaporController::class, "tanggal"])->name(
                    "dashboard.rapor.tanggal"
                );
                Route::post("/store", [
                    RaporController::class,
                    "storeTanggal",
                ])->name("dashboard.rapor.tanggal.store");
                Route::delete("/{id}", [
                    RaporController::class,
                    "destroyTanggal",
                ])->name("dashboard.rapor.tanggal.destroy");
            });

            // Route::prefix("arsip")->group(function () {
            //     Route::get("/", [ArsipController::class, "home"])
            //         ->name("dashboard.rapor.arsip")
            //         ->middleware(["role:ops"]);
            // });
        });
        Route::prefix("arsip")->group(function () {
            Route::get("/rapor", [ArsipController::class, "homeRapor"])
                    ->name("dashboard.rapor.arsip")
                    ->middleware(["role:ops"]);
            Route::prefix('ijazah')->group(function () {

                Route::get("/", [ArsipController::class, "homeIjazah"])
                        ->name("dashboard.arsip.ijazah")
                        ->middleware(["role:ops"]);

                Route::post('/store', [ArsipController::class, 'storeIjazah'])->name('dashboard.arsip.ijazah.store');
                Route::put('/{id}', [ArsipController::class, 'updateIjazah'])->name('dashboard.arsip.ijazah.update');
                Route::delete('/{id}', [ArsipController::class, 'destroyIjazah'])->name('dashboard.arsip.ijazah.destroy');
            });
        });

        // 7 Kaih
        Route::middleware(["role:guru_agama|guru_kelas"])
            ->prefix("kaih")
            ->group(function () {
                Route::get("/home", [KaihController::class, "home"])->name(
                    "dashboard.kaih.home"
                );
                Route::get("/bulan", [KaihController::class, "perBulan"])->name(
                    "dashboard.kaih.bulan"
                );
                Route::post("/rekap/input", [
                    KaihController::class,
                    "inputRekap",
                ])->name("dashboard.kaih.rekap.input");
                Route::get("/rekap/siswa", [
                    KaihController::class,
                    "rekapSiswa",
                ])->name("dashboard.kaih.rekap.siswa");
            });

        Route::prefix("roles")->group(function () {
            Route::get("/", [RoleController::class, "home"])->name(
                "dashboard.role"
            );
            Route::post("/store", [RoleController::class, "store"])
                ->name("dashboard.role.store")
                ->middleware("role:superadmin");
            Route::post("/permission/assign", [
                RoleController::class,
                "assignPermission",
            ])
                ->name("dashboard.role.permission.assign")
                ->middleware("role:superadmin");
        });

        Route::prefix("permissions")->group(function () {
            Route::get("/", [PermissionController::class, "home"])->name(
                "dashboard.permission"
            );
            Route::post("/store", [PermissionController::class, "store"])->name(
                "dashboard.permission.store"
            );
        });

        Route::prefix("backup")->group(function () {
            Route::get("/", [BackupController::class, "home"])
                ->name("dashboard.backup")
                ->middleware(["role:admin|superadmin"]);
            Route::post("/", [BackupController::class, "store"])
                ->name("dashboard.backup.store")
                ->middleware(["role:admin|superadmin"]);
            Route::post("/tes", [BackupController::class, "tes"])
                ->name("dashboard.backup.tes")
                ->middleware(["role:admin|superadmin"]);
        });

        Route::middleware("role:admin|superadmin")
            ->prefix("periode")
            ->group(function () {
                Route::get("/", [PeriodeController::class, "home"])->name(
                    "dashboard.setting.periode"
                );
                Route::put("/tapel/{id}", [
                    PeriodeController::class,
                    "toggleTapel",
                ])->name("dashboard.setting.tapel.toggle");
                Route::put("/semester/{id}", [
                    PeriodeController::class,
                    "toggleSemester",
                ])->name("dashboard.setting.semester.toggle");
            });

        Route::prefix("tapel")->group(function () {
            Route::post("/store", [TapelController::class, "store"])
                ->name("dashboard.tapel.store")
                ->middleware(["role:admin|superadmin"]);
            Route::put("/{id}/toggle", [TapelController::class, "toggle"])
                ->name("dashboard.tapel.toggle")
                ->middleware(["role:admin|superadmin"]);
        });
        Route::prefix("semester")->group(function () {
            Route::post("/store", [SemesterController::class, "store"])
                ->name("dashboard.semester.store")
                ->middleware(["role:admin|superadmin"]);
            Route::put("/{id}/toggle", [SemesterController::class, "toggle"])
                ->name("dashboard.semester.toggle")
                ->middleware(["role:admin|superadmin"]);
        });

        Route::prefix("user")->group(function () {
            Route::post("/store", [UserController::class, "store"])
                ->name("dashboard.user.store")
                ->middleware("role:superadmin");
            Route::post("/permission/assign", [
                UserController::class,
                "assignPermission",
            ])
                ->name("dashboard.user.permission.assign")
                ->middleware("role:superadmin");
        });

        Route::prefix("agenda")->group(function () {
            Route::get("/", [AgendaController::class, "home"])
                ->name("dashboard.agenda")
                ->middleware("role:admin|superadmin|ops");
            Route::post("/store", [AgendaController::class, "store"])
                ->name("dashboard.agenda.store")
                ->middleware("role:admin|superadmin|ops");
        });

        Route::prefix("p5")->group(function () {
            Route::get("/", [P5Controller::class, "home"])->name(
                "dashboard.p5"
            );
            Route::prefix("nilai")->group(function () {
                Route::get("/", [P5Controller::class, "nilai"])->name(
                    "dashboard.p5.nilai"
                );
                Route::post("/index", [
                    P5Controller::class,
                    "indexNilaiP5",
                ])->name("dashboard.p5.nilai.index");
                Route::post("/store", [
                    P5Controller::class,
                    "storeNilai",
                ])->name("dashboard.p5.nilai.store");
            });
            Route::prefix("proses")->group(function () {
                Route::post("/index", [
                    P5Controller::class,
                    "indexProses",
                ])->name("dashbaord.p5.proses.index");
            });
            Route::prefix("proyek")->group(function () {
                Route::get("/", [P5Controller::class, "proyek"])->name(
                    "dashboard.p5.proyek"
                );
                Route::post("/store", [
                    P5Controller::class,
                    "storeProyek",
                ])->name("dashboard.p5.proyek.store");
            });
            Route::prefix("apd")->group(function () {
                Route::post("/impor", [ApdController::class, "impor"])->name(
                    "dashboard.apd.impor"
                );
            });
        });

        // https://raporsd.test/dashboard/administrasi/presensiguru
        Route::prefix("administrasi")->group(function () {
            Route::get("/presensiguru", [
                AdministrasiController::class,
                "presensiGuru",
            ])
                ->middleware("role:ops|guru_agama")
                ->name("presensi.guru.index");
            Route::get("/presensiguru/unduh", [
                AdministrasiController::class,
                "unduhPresensi",
            ])
                ->middleware("role:ops|guru_agama")
                ->name("presensi.guru.download");
            Route::prefix("akhirjenjang")->group(function () {
                Route::get("/", [
                    AdministrasiController::class,
                    "homeAkhirJenjang",
                ])
                    ->middleware("role:guru_kelas")
                    ->name("dashboard.administrasi.akhirjenjang.home");
            });
        });

        // Sekolah Plus Ngaji
        Route::prefix("spn")
            ->middleware("role:guru_agama")
            ->group(function () {
                Route::get("/", [SpnController::class, "home"])->name(
                    "dashboard.spn.home"
                );

                Route::prefix("pretes")->group(function () {
                    Route::post("/siswa", [
                        SpnController::class,
                        "getSiswa",
                    ])->name("dashboard.spn.pretes.siswa");
                    Route::post("/siswa/attach-all", [
                        SpnController::class,
                        "storePretes",
                    ])->name("dashboard.spn.pretes.siswa.attach-all");
                    Route::post("/siswa/attach/{siswaId}", [
                        SpnController::class,
                        "attachSiswa",
                    ])->name("dashboard.spn.pretes.siswa.attach");
                });

                Route::prefix("jurnal")->group(function () {
                    Route::post("/store", [
                        SpnController::class,
                        "storeJurnal",
                    ])->name("dashboard.spn.jurnal.store");
                });
            });

        // Organisasi
        Route::prefix("organisasi")->group(function () {
            Route::get("/", [OrgController::class, "home"])->name(
                "dashboard.org"
            );
            Route::get("/nonmember", [OrgController::class, "nonMember"])->name(
                "dashboard.organisasi.nonmember"
            );
            Route::post("/member/store", [
                OrgController::class,
                "storeMember",
            ])->name("dashboard.organisasi.member.store");
        });

        // Cetak
    });
    Route::prefix("cetak")->group(function () {
        Route::get("/transkrip/{nisn}", [
            CetakController::class,
            "cetakTranskrip",
        ]);
        Route::prefix("rapor")->group(function () {
            Route::get("/{page}/{siswaId}", [
                RaporController::class,
                "cetakRapor",
            ]);
        });
        Route::get("/analisis-asesmen/{asesmenId}", [
            CetakController::class,
            "cetakAnalisisAsesmen",
        ]);

        Route::prefix("rekap")->group(function () {
            Route::get("/sekolah/rombel/siswa", [
                CetakController::class,
                "cetakRekapSekolahRombelSiswa",
            ]);
        });

        Route::prefix('ledger')->group(function () {
            Route::get('/piagam', [
                CetakController::class,
                'cetakPiagamRanking',
            ])->name('cetak.ledger.piagam');
        });
    });
});

require __DIR__ . "/auth.php";
require __DIR__ . "/siswa.php";
