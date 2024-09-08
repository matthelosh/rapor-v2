<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:siswa'])->group(
    function () {
        Route::get('asesmen', [AsesmenController::class, 'siswaAsesmen'])->name('asesmen.siswa');
    }
);
