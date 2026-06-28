@extends('layouts.app')

@section('title', 'Manajemen Kamar')
@section('subtitle', 'Kelola ketersediaan, tipe, dan harga kamar')

@section('content')
@php
    $total = $kamars->count();
    $tersedia = $kamars->where('status', 'tersedia')->count();
    $terisi = $kamars->where('status', 'terisi')->count();
    $potensi = $kamars->sum('harga');
@endphp

<div class="page-heading">
    <div>
        <h1>Manajemen Kamar</h1>
        <p>Monitor status hunian, tarif bulanan, dan data kamar yang siap dipasarkan.</p>
    </div>
    <a href="{{ route('kamar.create') }}" class="btn-primary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
        Tambah Kamar Baru
    </a>
</div>

<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">bed</span></div>
        <div class="metric-label">Total Kamar</div>
        <div class="metric-value">{{ $total }}</div>
        <div class="metric-note">Unit tercatat dalam sistem</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon success">task_alt</span></div>
        <div class="metric-label">Tersedia</div>
        <div class="metric-value">{{ $tersedia }}</div>
        <div class="metric-note">Siap ditempati</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon danger">person</span></div>
        <div class="metric-label">Terisi</div>
        <div class="metric-value">{{ $terisi }}</div>
        <div class="metric-note">Sedang dihuni</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon warning">payments</span></div>
        <div class="metric-label">Potensi Sewa</div>
        <div class="metric-value">Rp {{ number_format($potensi, 0, ',', '.') }}</div>
        <div class="metric-note">Jika semua kamar aktif</div>
    </div>
</div>

<div class="content-card">
    <div class="filter-box">
        <input class="compact-input" type="search" placeholder="Cari nomor kamar...">
        <select class="compact-input">
            <option>Semua Status</option>
            <option>Tersedia</option>
            <option>Terisi</option>
        </select>
        <select class="compact-input">
            <option>Semua Tipe</option>
            <option>Single</option>
            <option>Double</option>
        </select>
        <span class="ms-auto text-muted small fw-semibold">Menampilkan {{ $kamars->count() }} data kamar</span>
    </div>
    <div class="content-card-body flush">
        @if($kamars->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe</th>
                        <th>Harga / Bulan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kamars as $i => $kamar)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="table-title">
                                <span class="row-avatar">{{ strtoupper(substr($kamar->nomor_kamar, 0, 2)) }}</span>
                                Kamar {{ $kamar->nomor_kamar }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-status {{ $kamar->tipe == 'single' ? 'badge-blue' : 'badge-warning' }}">
                                {{ ucfirst($kamar->tipe) }}
                            </span>
                        </td>
                        <td class="fw-bold">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-status {{ $kamar->status == 'tersedia' ? 'badge-tersedia' : 'badge-terisi' }}">
                                {{ ucfirst($kamar->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons justify-content-end">
                                <a href="{{ route('kamar.edit', $kamar) }}" class="btn-action btn-edit" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                </a>
                                <form action="{{ route('kamar.destroy', $kamar) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                        <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <span class="material-symbols-outlined">bed</span>
            <h6>Belum ada data kamar</h6>
            <p>Klik tombol tambah kamar untuk membuat data pertama.</p>
        </div>
        @endif
    </div>
</div>
@endsection
