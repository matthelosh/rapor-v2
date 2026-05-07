<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:siswa'])->group(
    function () {
        Route::prefix('asesmen')->group(
            function () {

                Route::get('/', [AsesmenController::class, 'siswaAsesmen'])->name('asesmen.siswa');
                Route::get('/kerjakan', [AsesmenController::class, 'kerjakanAsesmen'])->name('asesmen.siswa.kerjakan');
                Route::post('/jawaban/temp/store', [AsesmenController::class, 'saveTemp'])->name('asesmen.siswa.jawaban.savetemp');
                Route::post('/{kode}/kerjakan/mulai', [AsesmenController::class, 'mulaiKerjakan'])->name('asesmen.siswa.kerjakan.mulai');
            }
        );
    }
);
