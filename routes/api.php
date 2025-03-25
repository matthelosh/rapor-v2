<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiKeyVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::middleware(ApiKeyVerified::class)->group(function () {
Route::apiResource('/posts', PostController::class);

Route::apiResource('/sekolah', SekolahController::class);

//Route::get('/rombel', [RombelController::class, 'index'])->middleware(ApiKeyVerified::class);
//});
//
Route::get('/rombel', [ClientController::class, 'getRombel'])->middleware('verify_api_key');
Route::get('/tps', [ClientController::class, 'getTp'])->middleware('verify_api_key');
Route::get('/asesmens', [ClientController::class, 'getAsesmen'])->middleware('verify_api_key');
Route::get('/kaldik', [ClientController::class, 'getKaldik'])->middleware('verify_api_key');

// Sync Nilai
Route::prefix('nilai')->group(
    function () {
        Route::prefix("pts")->group(
            function () {
                Route::post("/store", [ClientController::class, 'storeNilai']);
                // Route::post("/store", function (Request $request) {
                //     return response()->json([
                //         'status' => 'success',
                //         'message' => 'Halo'
                //     ], 200);
                // });
            }
        );
    }
);

Route::middleware('verify_api_key')->prefix('dapo')->group(
    function () {
        Route::post('/sekolah/sync', [DaposyncController::class, 'syncSekolah']);
        Route::post('/guru/sync', [DaposyncController::class, 'syncGuru']);
        Route::post('/rombel/sync', [DaposyncController::class, 'syncRombel']);
        Route::post('/siswa/sync', [DaposyncController::class, 'syncSiswa']);
    }
);
