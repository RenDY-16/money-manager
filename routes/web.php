<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BackupController;

Route::get('/', fn () => view('landing'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kamar', KamarController::class)->except(['show']);
    Route::resource('penghuni', PenghuniController::class)->except(['show']);
    Route::resource('pemasukan', PemasukanController::class)->except(['show']);
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    Route::get('/backup-data', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup-data/download-json', [BackupController::class, 'downloadJson'])->name('backup.download.json');
    Route::post('/backup-data/restore-json', [BackupController::class, 'restoreJson'])->name('backup.restore.json');

    Route::get('/pemasukan/{pemasukan}/kwitansi', [PemasukanController::class, 'showKwitansi'])->name('pemasukan.kwitansi');

    Route::get('/profil-admin', [ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil-admin', [ProfileController::class, 'updateProfile'])->name('profil.update');
    Route::put('/profil-admin/password', [ProfileController::class, 'updatePassword'])->name('profil.password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
