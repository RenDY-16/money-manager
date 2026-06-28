@extends('layouts.app')

@section('title', 'Backup Data')
@section('subtitle', 'Unduh cadangan data utama sistem kost')

@section('content')
<div class="page-heading">
    <div>
        <h1>Backup Data</h1>
        <p>Gunakan fitur ini untuk mengunduh salinan data utama aplikasi dalam format JSON.</p>
    </div>
    <a href="{{ route('backup.download.json') }}" class="btn-primary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">download</span>
        Download Backup JSON
    </a>
</div>

<div class="metric-grid metric-grid-5 mb-4">
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">bed</span></div>
        <div class="metric-label">Data Kamar</div>
        <div class="metric-value">{{ $summary['kamar'] }}</div>
        <div class="metric-note">Unit kamar tersimpan</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon success">groups</span></div>
        <div class="metric-label">Data Penghuni</div>
        <div class="metric-value">{{ $summary['penghuni'] }}</div>
        <div class="metric-note">Penghuni aktif dan historis</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">payments</span></div>
        <div class="metric-label">Pemasukan</div>
        <div class="metric-value">{{ $summary['pemasukan'] }}</div>
        <div class="metric-note">Transaksi masuk</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon danger">account_balance_wallet</span></div>
        <div class="metric-label">Pengeluaran</div>
        <div class="metric-value">{{ $summary['pengeluaran'] }}</div>
        <div class="metric-note">Transaksi keluar</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon warning">admin_panel_settings</span></div>
        <div class="metric-label">Admin</div>
        <div class="metric-value">{{ $summary['admin'] }}</div>
        <div class="metric-note">Akun pengelola</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">backup</span> Paket Backup</h5>
            </div>
            <div class="content-card-body">
                <p class="text-muted fw-semibold">File backup JSON berisi data tabel berikut:</p>
                <div class="table-scroll">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><span class="badge-status badge-blue">users</span></td><td>Nama, email, dan foto profil admin tanpa password.</td></tr>
                            <tr><td><span class="badge-status badge-blue">kamars</span></td><td>Nomor kamar, tipe, harga, dan status kamar.</td></tr>
                            <tr><td><span class="badge-status badge-blue">penghunis</span></td><td>Identitas penghuni, nomor HP, kamar, dan tanggal masuk/keluar.</td></tr>
                            <tr><td><span class="badge-status badge-success">pemasukans</span></td><td>Transaksi pemasukan, kategori, penghuni, tanggal, dan nominal.</td></tr>
                            <tr><td><span class="badge-status badge-danger">pengeluarans</span></td><td>Transaksi pengeluaran, kategori, tanggal, dan nominal.</td></tr>
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('backup.download.json') }}" class="btn-primary-custom mt-4">
                    <span class="material-symbols-outlined" style="font-size:18px;">download</span>
                    Download Backup Sekarang
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">info</span> Catatan Backup</h5>
            </div>
            <div class="content-card-body">
                <div class="finance-panel mb-3">
                    <div class="label"><span class="material-symbols-outlined">schedule</span> Update Terakhir</div>
                    <div class="value" style="font-size:18px;">
                        {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->locale('id')->translatedFormat('d M Y H:i') : 'Belum ada data' }}
                    </div>
                </div>
                <p class="text-muted fw-semibold mb-2">Saran penggunaan:</p>
                <ul class="text-muted fw-semibold" style="line-height:1.8;">
                    <li>Download backup sebelum melakukan perubahan besar.</li>
                    <li>Simpan file backup di Google Drive atau penyimpanan eksternal.</li>
                    <li>Jangan bagikan file backup karena berisi data penghuni dan transaksi.</li>
                    <li>Untuk pemulihan data otomatis, perlu fitur restore terpisah agar aman.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
