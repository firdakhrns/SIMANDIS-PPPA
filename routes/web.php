<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\CetakController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mading', [AgendaController::class, 'index'])->name('mading.index');
    
    Route::get('/cetak/kegiatan/{id}', [CetakController::class, 'pdfKegiatan'])->name('cetak.kegiatan');
    Route::get('/cetak/bulanan', [CetakController::class, 'pdfBulanan'])->name('cetak.bulanan');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/agenda/create', [AgendaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
    });

    Route::middleware(['role:kadis'])->group(function () {
        Route::get('/disposisi/{id}/isi', [DisposisiController::class, 'edit'])->name('disposisi.edit');
        Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update');
    });

    Route::middleware(['role:user'])->group(function () {
        Route::get('/realisasi/{agenda_id}/isi', [RealisasiController::class, 'create'])->name('realisasi.create');
        Route::post('/realisasi/{agenda_id}', [RealisasiController::class, 'store'])->name('realisasi.store');
    });
});