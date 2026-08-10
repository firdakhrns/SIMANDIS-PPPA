<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\CetakController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/arsip-surat', [SuratController::class, 'index'])->name('surat.index');
    Route::post('/arsip-surat', [SuratController::class, 'store'])->name('surat.store');
    
    Route::get('/surat/{id}/preview', [SuratController::class, 'preview'])->name('surat.preview');
    Route::get('/surat/{id}/download', [SuratController::class, 'download'])->name('surat.download');
    
    Route::get('/mading', [AgendaController::class, 'index'])->name('mading.index');
    Route::get('/mading-bidang', [AgendaController::class, 'index'])->name('mading.bidang');
    Route::patch('/agenda/{id}/toggle-status', [AgendaController::class, 'toggleStatus'])->name('agenda.toggle-status');
 
    Route::get('/disposisi/{id}/cetak', [DisposisiController::class, 'cetak'])->name('disposisi.cetak');

    Route::middleware(['role:admin,user'])->group(function () {
        Route::get('/agenda/create', [AgendaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
    });

    Route::middleware(['role:admin,kadis'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/events-kalender', [DashboardController::class, 'getEvents'])->name('api.kalender');
        Route::get('/cetak/kalender', [CetakController::class, 'pdfKalender'])->name('cetak.kalender');

        Route::get('/disposisi/{id}/isi', [DisposisiController::class, 'edit'])->name('disposisi.edit');
        Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update');
    });
});