<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SekolahController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
        Route::prefix('operator')->group(function () {
            Route::get("/", [DashboardController::class, 'operator'])->middleware(['role:admin|ops'])->name('dashboard.operator');
        });
        Route::prefix('sekolah')->group(function () {
            Route::get("/", [SekolahController::class, 'home'])->middleware(['role:admin|ops'])->name('dashboard.sekolah');
            Route::post("/index", [SekolahController::class, 'index'])->name('dashboard.sekolah.index');
            Route::post('/', [SekolahController::class, 'store'])->name('dashboard.sekolah.store');
            Route::put('/', [SekolahController::class, 'update'])->middleware(['role:admin|ops'])->name('dashboard.sekolah.update');
            Route::post('/impor', [SekolahController::class, 'impor'])->name('dashboard.sekolah.impor');
            Route::delete('/{id}', [SekolahController::class, 'destroy'])->middleware(['role:admin'])->name('dashboard.sekolah.destroy');
            Route::post('/{id}/operator', [SekolahController::class, 'addOps'])->middleware(['role:admin'])->name('dashboard.sekolah.ops.add');
        });

        Route::prefix("guru")->group(function () {
            Route::get("/", [GuruController::class, 'index'])->name('dashboard.guru');
            Route::post('/', [GuruController::class, 'store'])->name('dashboard.guru.store')->middleware(['role:admin|ops']);
            Route::post('/get', [GuruController::class, 'show'])->name('dashboard.guru.show');
            Route::put('/', [GuruController::class, 'update'])->name('dashboard.guru.update');
            Route::post('/impor', [GuruController::class, 'impor'])->name('dashboard.guru.impor');
            Route::post('/account/add', [GuruController::class, 'addAccount'])->name('dashboard.guru.account.add')->middleware(['role:admin|ops']);
            Route::delete('/{id}', [GuruController::class, 'destroy'])->name('dashboard.guru.destroy')->middleware(['role:admin|ops']);
        });

        Route::prefix("siswa")->group(function () {
            Route::get("/", [SiswaController::class, 'home'])->name('dashboard.siswa');
            Route::post("/", [SiswaController::class, 'store'])->name('dashboard.siswa.store');
            Route::post("/nonmember", [SiswaController::class, 'nonMember'])->name('dashboard.siswa.nonmember');
            Route::put("/", [SiswaController::class, 'update'])->name('dashboard.siswa.update');
            Route::post("/impor", [SiswaController::class, 'impor'])->name('dashboard.siswa.impor');
            Route::delete("/{id}", [SiswaController::class, 'destroy'])->name('dashboard.siswa.destroy');
            Route::prefix('ortu')->group(function () {
                Route::post('/impor', [OrtuController::class, 'impor'])->name('dashboard.siswa.ortu.impor');
            });
        });

        Route::prefix("rombel")->group(function () {
            Route::get("/", [RombelController::class, 'home'])->name('dashboard.rombel');
            Route::post("/", [RombelController::class, 'store'])->name('dashboard.rombel.store');
            Route::post("/member/attach", [RombelController::class, 'attachMember'])->name('dashboard.rombel.member.attach');
            Route::post("/member/detach", [RombelController::class, 'detachMember'])->name('dashboard.rombel.member.detach');
            Route::put("/", [RombelController::class, 'update'])->name('dashboard.rombel.update');
            Route::delete("/{id}", [RombelController::class, 'destroy'])->name('dashboard.rombel.destroy');
        });

        Route::prefix('pembelajaran')->group(function () {
            Route::get('/', [PembelajaranController::class, 'home'])->name('dashboard.pembelajaran');
            Route::post('/elemen/impor', [ElemenController::class, 'impor'])->name('dashboard.pembelajaran.elemen.impor');
            Route::post('/mapel/assign', [PembelajaranController::class, 'assignMapel'])->name('dashboard.pembelajaran.mapel.assign');
            Route::post('/tp', [TpController::class, 'index'])->name('dashboard.pembelajaran.tp.index');
            Route::post('/tp/impor', [TpController::class, 'impor'])->name('dashboard.pembelajaran.tp.impor');
            Route::post('/tp/store', [TpController::class, 'store'])->name('dashboard.pembelajaran.tp.store');
            Route::delete('/tp/{id}', [TpController::class, 'destroy'])->name('dashboard.pembelajaran.tp.destroy');
        });

        Route::prefix("nilai")->group(function () {
            Route::get("/", [NilaiController::class, "home"])->name('dashboard.nilai')->middleware(['role:guru_kelas|guru_agama|guru_pjok|guru_inggris']);
            Route::post("/", [NilaiController::class, "index"])->name('dashboard.nilai.index')->middleware(['role:guru_kelas|guru_agama|guru_pjok|guru_inggris']);
            Route::post("/store", [NilaiController::class, "store"])->name('dashboard.nilai.store')->middleware(['role:guru_kelas|guru_agama|guru_pjok|guru_inggris']);
        });

        Route::prefix("ledger")->group(function () {
            Route::get("/", [LedgerController::class, "home"])->name('dashboard.ledger')->middleware('role:guru_kelas|guru_agama|guru_pjok|guru_inggris');
        });

        Route::prefix('rapor')->group(function () {
            Route::get('/cetak', [RaporController::class, 'home'])->name('dashboard.rapor.cetak')->middleware(['role:guru_kelas']);
            Route::get('/periodik', [RaporController::class, 'periodik'])->name('dashboard.rapor.periodik')->middleware(['role:guru_kelas']);
        })->middleware(['role:guru_kelas']);
    });
});

require __DIR__ . '/auth.php';
