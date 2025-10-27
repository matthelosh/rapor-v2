<?php

namespace App\Http\Controllers\Api;

use App\Models\Rombel;
use App\Models\User;
use App\Helpers\Periode;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Middleware\ApiKeyVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// ===========================================================
// PUBLIC API - Untuk aplikasi eksternal (statistik, data dasar)
// ===========================================================
Route::get('/sekolah/subdomain/{subdomain}', [SekolahController::class, 'showBySubdomain']);
Route::middleware('verify_api_key')->group(function () {
    // Data dasar untuk statistik halaman depan
    Route::get('/rombel', [ClientController::class, 'getRombel']);
    Route::get('/tps', [ClientController::class, 'getTp']);
    Route::get('/asesmens', [ClientController::class, 'getAsesmen']);
    Route::get('/kaldik', [ClientController::class, 'getKaldik']);

    // Data sekolah
    // Route::get('/sekolah/{npsn}', function(Request $request, $npsn) {
    //     return $npsn;
    // });
    Route::apiResource('/sekolah', SekolahController::class);

    // Posts
    Route::apiResource('/posts', PostController::class);

    // Sync data (untuk aplikasi eksternal)
    Route::prefix('sync')->group(function () {
        Route::prefix('nilai')->group(function () {
            Route::prefix('pts')->group(function () {
                Route::post('/store', [ClientController::class, 'storeNilai']);
            });
        });

        Route::prefix('dapo')->group(function () {
            Route::post('/sekolah/sync', [DaposyncController::class, 'syncSekolah']);
            Route::post('/guru/sync', [DaposyncController::class, 'syncGuru']);
            Route::post('/rombel/sync', [DaposyncController::class, 'syncRombel']);
            Route::post('/siswa/sync', [DaposyncController::class, 'syncSiswa']);
        });
    });
});

// ===========================================================
// PROTECTED API - Butuh login user
// ===========================================================
Route::middleware('auth:api')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/me', function (Request $request) {
        $user = $request->user();
        $detail = $user->userable;
        $rombel = Rombel::whereHas('siswas', function ($query) use ($detail) {
            $query->where('siswas.id', $detail->id);
        })->whereTapel(Periode::tapel()->kode)->first();
        $detail->rombel = $rombel;
        return response()->json(['detail' => $detail]);
    });

    // Mapel (butuh login user)
    Route::get('/mapels', [MapelController::class, 'index']);

    // Kaih (siswa only)
    Route::middleware('role:siswa')->prefix('kaih')->group(function () {
        Route::get('/', [KaihController::class, 'index'])->name('api.kaih.index');
        Route::post('/store', [KaihController::class, 'store'])->name('api.kaih.store');
    });

    // Presensi (guru only)
    Route::middleware('role:guru_kelas|guru_agama|guru_pjok|guru_inggris')->prefix('presensi')->group(function () {
        Route::get('/', [PresensiController::class, 'index'])->name('api.presensi.index');
        Route::post('/store', [PresensiController::class, 'store'])->name('api.presensi.store');
        Route::get('/rombels', [PresensiController::class, 'getRombels'])->name('api.presensi.rombels');
        Route::get('/harian', [PresensiController::class, 'harian'])->name('api.presensi.harian');
        Route::post('/rekap-presensi-siswa', [PresensiController::class, 'rekapPresensiSiswa'])->name('api.presensi.rekap-presensi-siswa');
        Route::post('/rekap-bulan', [PresensiController::class, 'rekapBulan'])->name('api.presensi.rekap-bulan');
    });

    // Jurnal Mengajar (guru only)
    Route::middleware('role:guru_kelas|guru_agama|guru_pjok|guru_inggris')->prefix('jurnal-mengajar')->group(function () {
        Route::get('/', [JurnalMengajarController::class, 'index'])->name('api.jurnal-mengajar.index');
        Route::post('/store', [JurnalMengajarController::class, 'store'])->name('api.jurnal-mengajar.store');
        Route::get('/{id}', [JurnalMengajarController::class, 'show'])->name('api.jurnal-mengajar.show');
        Route::put('/{id}', [JurnalMengajarController::class, 'update'])->name('api.jurnal-mengajar.update');
        Route::delete('/{id}', [JurnalMengajarController::class, 'destroy'])->name('api.jurnal-mengajar.destroy');
    });
});

// ===========================================================
// AUTHENTICATION - No middleware required
// ===========================================================
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// ===========================================================
// BEARER TOKEN API - Using simple bearer token authentication
// ===========================================================
Route::middleware('auth.bearer')->prefix('asesmen')->group(function () {
    Route::get('/', [AsesmenController::class, 'index']);
    Route::post('/store', [AsesmenController::class, 'store']);
    Route::get('/syncsekolah', [AsesmenController::class, 'syncSekolah']);
    Route::get('/syncsiswa', [AsesmenController::class, 'syncSiswa']);
    Route::get('/periode', [AsesmenController::class, 'periode']);
});
