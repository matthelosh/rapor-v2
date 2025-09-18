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

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:api");

//Route::middleware(ApiKeyVerified::class)->group(function () {
Route::apiResource("/posts", PostController::class);

Route::apiResource("/sekolah", SekolahController::class);
Route::get("/sekolah/subdomain/{subdomain}", [
    SekolahController::class,
    "showBySubdomain",
])->name("sekolah.showBySubdomain");

//Route::get('/rombel', [RombelController::class, 'index'])->middleware(ApiKeyVerified::class);
//});
//
Route::get("/rombel", [ClientController::class, "getRombel"])->middleware(
    "verify_api_key",
);
Route::get("/tps", [ClientController::class, "getTp"])->middleware(
    "verify_api_key",
);
Route::get("/mapels", [MapelController::class, "index"])->middleware(
    "auth:api",
);
Route::get("/asesmens", [ClientController::class, "getAsesmen"])->middleware(
    "verify_api_key",
);
Route::get("/kaldik", [ClientController::class, "getKaldik"])->middleware(
    "verify_api_key",
);

// Sync Nilai
Route::prefix("nilai")->group(function () {
    Route::prefix("pts")->group(function () {
        Route::post("/store", [ClientController::class, "storeNilai"]);
        // Route::post("/store", function (Request $request) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Halo'
        //     ], 200);
        // });
    });
});

Route::middleware("verify_api_key")
    ->prefix("dapo")
    ->group(function () {
        Route::post("/sekolah/sync", [
            DaposyncController::class,
            "syncSekolah",
        ]);
        Route::post("/guru/sync", [DaposyncController::class, "syncGuru"]);
        Route::post("/rombel/sync", [DaposyncController::class, "syncRombel"]);
        Route::post("/siswa/sync", [DaposyncController::class, "syncSiswa"]);
    });

// API Authentication
Route::post("/login", [AuthController::class, "login"])->name("api.login");

Route::middleware(["auth:api"])->get("/me", function (Request $request) {
    $user = $request->user();
    $detail = $user->userable;
    $rombel = Rombel::whereHas("siswas", function ($query) use ($detail) {
        $query->where("siswas.id", $detail->id);
    })
        ->whereTapel(Periode::tapel()->kode)
        ->first();
    $detail->rombel = $rombel;
    return \response()->json([
        "detail" => $detail,
    ]);
});

// Kaih
Route::middleware(["auth:api", "role:siswa"])
    ->prefix("kaih")
    ->group(function () {
        Route::get("/", [KaihController::class, "index"])->name(
            "api.kaih.index",
        );

        Route::post("/store", [KaihController::class, "store"])->name(
            "api.kaih.store",
        );
    });

// Presensi
Route::middleware(["auth:api", "role:guru_kelas|guru_agama|guru_pjok|guru_inggris"])
    ->prefix("presensi")
    ->group(function () {
        Route::get("/", [PresensiController::class, "index"])->name(
            "api.presensi.index",
        );

        Route::post("/store", [PresensiController::class, "store"])->name(
            "api.presensi.store",
        );

        Route::get("/rombels", [PresensiController::class, "getRombels"])->name(
            "api.presensi.rombels",
        );

        Route::get('/harian', [PresensiController::class, 'harian'])->name('api.presensi.harian');
    });

// Jurnal Mengajar
Route::middleware(["auth:api", "role:guru_kelas|guru_agama|guru_pjok|guru_inggris"])
    ->prefix("jurnal-mengajar")
    ->group(function () {
        Route::get("/", [JurnalMengajarController::class, "index"])->name(
            "api.jurnal-mengajar.index",
        );

        Route::post("/store", [JurnalMengajarController::class, "store"])->name(
            "api.jurnal-mengajar.store",
        );

        Route::get("/{id}", [JurnalMengajarController::class, "show"])->name(
            "api.jurnal-mengajar.show",
        );

        Route::put("/{id}", [JurnalMengajarController::class, "update"])->name(
            "api.jurnal-mengajar.update",
        );

        Route::delete("/{id}", [JurnalMengajarController::class, "destroy"])->name(
            "api.jurnal-mengajar.destroy",
        );
    });

Route::middleware(["auth.bearer"])->group(function () {
    Route::prefix("asesmen")->group(function () {
        Route::get("/", [AsesmenController::class, "index"]);
        Route::post("/store", [AsesmenController::class, "store"]);
        Route::get("/syncsekolah", [AsesmenController::class, "syncSekolah"]);
        Route::get("/periode", [AsesmenController::class, "periode"]);
    });
});
