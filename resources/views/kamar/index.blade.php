@extends('layouts.app')

@section('title', 'Manajemen Kamar')
@section('subtitle', 'Kelola ketersediaan, tipe, dan harga kamar')

@section('content')
@php
    $statSource = $allKamars ?? $kamars;
    $total = $statSource->count();
    $tersedia = $statSource->where('status', 'tersedia')->count();
    $terisi = $statSource->where('status', 'terisi')->count();
    $potensi = $statSource->sum('harga');
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
    <form method="GET" action="{{ route('kamar.index') }}" class="filter-box">
        <input class="compact-input" name="search" type="search" value="{{ request('search') }}" placeholder="Cari nomor kamar...">
        <select class="compact-input" name="status">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="terisi" {{ request('status') === 'terisi' ? 'selected' : '' }}>Terisi</option>
        </select>
        <select class="compact-input" name="tipe">
            <option value="">Semua Tipe</option>
            <option value="single" {{ request('tipe') === 'single' ? 'selected' : '' }}>Single</option>
            <option value="double" {{ request('tipe') === 'double' ? 'selected' : '' }}>Double</option>
        </select>
        <button type="submit" class="btn-primary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">filter_alt</span>
            Terapkan Filter
        </button>
        <a href="{{ route('kamar.index') }}" class="btn-secondary-custom">Reset</a>
        <span class="ms-auto text-muted small fw-semibold">Menampilkan {{ $kamars->count() }} data kamar</span>
    </form>
    <div class="content-card-body flush">
        @if($kamars->count() > 0)
        <div class="table-scroll">
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
            <h6>Data tidak ditemukan</h6>
            <p>Ubah filter atau tambah data kamar baru.</p>
        </div>
        @endif
    </div>
</div>
@endsection
