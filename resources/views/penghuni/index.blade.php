@extends('layouts.app')

@section('title', 'Data Penghuni')
@section('subtitle', 'Kelola identitas penghuni dan histori masa tinggal')

@section('content')
@php
    $aktif = $penghunis->whereNull('tanggal_keluar')->count();
    $nonaktif = $penghunis->whereNotNull('tanggal_keluar')->count();
    $total = $penghunis->count();
    $kamarAktif = $penghunis->whereNull('tanggal_keluar')->pluck('kamar_id')->unique()->count();
@endphp

<div class="page-heading">
    <div>
        <h1>Data Penghuni</h1>
        <p>Kelola data penghuni aktif, kamar yang ditempati, dan tanggal masuk.</p>
    </div>
    <a href="{{ route('penghuni.create') }}" class="btn-primary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
        Tambah Penghuni
    </a>
</div>

<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">groups</span></div>
        <div class="metric-label">Total Penghuni</div>
        <div class="metric-value">{{ $total }}</div>
        <div class="metric-note">Semua data penghuni</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon success">verified_user</span></div>
        <div class="metric-label">Penghuni Aktif</div>
        <div class="metric-value">{{ $aktif }}</div>
        <div class="metric-note">Masih tinggal di kost</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon warning">logout</span></div>
        <div class="metric-label">Sudah Keluar</div>
        <div class="metric-value">{{ $nonaktif }}</div>
        <div class="metric-note">Memiliki tanggal keluar</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">bedroom_parent</span></div>
        <div class="metric-label">Kamar Aktif</div>
        <div class="metric-value">{{ $kamarAktif }}</div>
        <div class="metric-note">Ditempati penghuni aktif</div>
    </div>
</div>

<div class="content-card">
    <div class="filter-box">
        <input class="compact-input" type="search" placeholder="Cari nama penghuni...">
        <select class="compact-input">
            <option>Semua Status</option>
            <option>Aktif</option>
            <option>Sudah Keluar</option>
        </select>
        <span class="ms-auto text-muted small fw-semibold">Menampilkan {{ $penghunis->count() }} data penghuni</span>
    </div>
    <div class="content-card-body flush">
        @if($penghunis->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Kamar</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penghunis as $i => $penghuni)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="table-title">
                                <span class="row-avatar">{{ strtoupper(substr($penghuni->nama, 0, 2)) }}</span>
                                <div>
                                    {{ $penghuni->nama }}
                                    <div class="text-muted small fw-semibold">ID: PGH-{{ str_pad($penghuni->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $penghuni->no_hp }}</td>
                        <td>
                            @if($penghuni->kamar)
                                <span class="badge-status badge-blue">Kamar {{ $penghuni->kamar->nomor_kamar }}</span>
                            @else
                                <span class="badge-status badge-neutral">Kamar Terhapus</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($penghuni->tanggal_masuk)->locale('id')->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($penghuni->tanggal_keluar)
                                <span class="badge-status badge-danger">Keluar</span>
                            @else
                                <span class="badge-status badge-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons justify-content-end">
                                <a href="{{ route('penghuni.edit', $penghuni) }}" class="btn-action btn-edit" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                </a>
                                <form action="{{ route('penghuni.destroy', $penghuni) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penghuni ini? Status kamar akan dikembalikan menjadi tersedia.')">
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
            <span class="material-symbols-outlined">groups</span>
            <h6>Belum ada data penghuni</h6>
            <p>Tambahkan penghuni baru untuk mulai mengelola hunian.</p>
        </div>
        @endif
    </div>
</div>
@endsection
