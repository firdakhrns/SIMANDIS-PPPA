<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\CetakController;

// AUTHENTICATION ROUTES
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    
    // Mading Utama & Mading Bidang
    Route::get('/mading', [AgendaController::class, 'index'])->name('mading.index');
    Route::get('/mading-bidang', [AgendaController::class, 'index'])->name('mading.bidang');
    Route::patch('/agenda/{id}/toggle-status', [AgendaController::class, 'toggleStatus'])->name('agenda.toggle-status');
    
    // Cetak PDF
    Route::get('/cetak/kegiatan/{id}', [CetakController::class, 'pdfKegiatan'])->name('cetak.kegiatan');
    Route::get('/cetak/bulanan', [CetakController::class, 'pdfBulanan'])->name('cetak.bulanan');

    // ----------------------------------------------------------------------
    // AKSEBIDANG (USER) & ADMIN: FORM, SIMPAN, EDIT & HAPUS AGENDA
    // ----------------------------------------------------------------------
    Route::middleware(['role:admin,user'])->group(function () {
        Route::get('/agenda/create', [AgendaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
    });

    // ----------------------------------------------------------------------
    // FITUR EKSEKUTIF: ADMIN & KEPALA DINAS (KADIS)
    // ----------------------------------------------------------------------
    Route::middleware(['role:admin,kadis'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/events-kalender', [DashboardController::class, 'getEvents'])->name('api.kalender');
        Route::get('/cetak/kalender', [CetakController::class, 'pdfKalender'])->name('cetak.kalender');

        Route::get('/disposisi/{id}/isi', [DisposisiController::class, 'edit'])->name('disposisi.edit');
        Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update');
    });
});