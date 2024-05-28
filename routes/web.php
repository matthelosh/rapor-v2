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
    
    Route::prefix('dashboard')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
        Route::prefix('sekolah')->group(function() {
            Route::get("/", [SekolahController::class, 'index'])->name('dashboard.sekolah');
            Route::post('/', [SekolahController::class, 'store'])->name('dashboard.sekolah.store');
            Route::put('/', [SekolahController::class, 'update'])->name('dashboard.sekolah.update');
            Route::post('/impor', [SekolahController::class, 'impor'])->name('dashboard.sekolah.impor');
            Route::delete('/{id}', [SekolahController::class, 'destroy'])->name('dashboard.sekolah.destroy');
        });
    });
});

require __DIR__.'/auth.php';
