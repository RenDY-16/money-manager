<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

Route::get('/', [DashboardController::class, 'index']);

Route::resource('kamar', KamarController::class);
Route::resource('penghuni', PenghuniController::class);
Route::resource('pemasukan', PemasukanController::class);
Route::resource('pengeluaran', PengeluaranController::class);
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
