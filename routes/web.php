<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

Route::get('/', fn () => view('landing'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kamar', KamarController::class)->except(['show']);
    Route::resource('penghuni', PenghuniController::class)->except(['show']);
    Route::resource('pemasukan', PemasukanController::class)->except(['show']);
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::view('/profil-admin', 'profil.index')->name('profil.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
