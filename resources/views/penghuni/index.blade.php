@extends('layouts.app')

@section('title', 'Data Penghuni')
@section('subtitle', 'Kelola identitas penghuni dan status pembayaran')

@section('content')
@php
    $statSource = $allPenghunis ?? $penghunis;
    $aktif = $statSource->whereNull('tanggal_keluar')->count();
    $nonaktif = $statSource->whereNotNull('tanggal_keluar')->count();
    $total = $statSource->count();
    $lunas = $statSource->whereNull('tanggal_keluar')->where('status_pembayaran_bulan_ini', 'lunas')->count();
    $belumLunas = $statSource->whereNull('tanggal_keluar')->where('status_pembayaran_bulan_ini', 'belum_lunas')->count();
@endphp

<div class="page-heading">
    <div>
        <h1>Data Penghuni</h1>
        <p>Kelola data penghuni aktif, kamar yang ditempati, dan status pembayaran bulan {{ $periodeTagihan ?? now()->format('F Y') }}.</p>
    </div>
    <a href="{{ route('penghuni.create') }}" class="btn-primary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
        Tambah Penghuni
    </a>
</div>

<div class="metric-grid metric-grid-5">
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
        <div class="metric-top"><span class="material-symbols-outlined metric-icon danger">logout</span></div>
        <div class="metric-label">Sudah Keluar</div>
        <div class="metric-value">{{ $nonaktif }}</div>
        <div class="metric-note">Memiliki tanggal keluar</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon success">check_circle</span></div>
        <div class="metric-label">Lunas Bulan Ini</div>
        <div class="metric-value">{{ $lunas }}</div>
        <div class="metric-note">Pembayaran kost tercatat</div>
    </div>
    <div class="metric-card">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon warning">pending_actions</span></div>
        <div class="metric-label">Belum Lunas</div>
        <div class="metric-value">{{ $belumLunas }}</div>
        <div class="metric-note">Perlu diingatkan</div>
    </div>
</div>

<div class="content-card">
    <form method="GET" action="{{ route('penghuni.index') }}" class="filter-box">
        <input class="compact-input" name="search" type="search" value="{{ request('search') }}" placeholder="Cari nama, HP, atau kamar...">
        <select class="compact-input" name="status">
            <option value="">Semua Status Tinggal</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Sudah Keluar</option>
        </select>
        <select class="compact-input" name="pembayaran">
            <option value="">Semua Status Bayar</option>
            <option value="lunas" {{ request('pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum_lunas" {{ request('pembayaran') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
        </select>
        <button type="submit" class="btn-primary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">filter_alt</span>
            Terapkan Filter
        </button>
        <a href="{{ route('penghuni.index') }}" class="btn-secondary-custom">Reset</a>
        <span class="ms-auto text-muted small fw-semibold">Menampilkan {{ $penghunis->count() }} data penghuni</span>
    </form>
    <div class="content-card-body flush">
        @if($penghunis->count() > 0)
        <div class="table-scroll">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Kamar</th>
                        <th>Tanggal Masuk</th>
                        <th>Status Tinggal</th>
                        <th>Status Bayar</th>
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
                                    <div class="cell-muted">ID: PGH-{{ str_pad($penghuni->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $penghuni->no_hp }}</td>
                        <td>
                            @if($penghuni->kamar)
                                <span class="badge-status badge-blue">Kamar {{ $penghuni->kamar->nomor_kamar }}</span>
                                <div class="cell-muted">Rp {{ number_format($penghuni->kamar->harga, 0, ',', '.') }}/bulan</div>
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
                            @if($penghuni->tanggal_keluar)
                                <span class="badge-status badge-neutral">Tidak Aktif</span>
                            @elseif($penghuni->status_pembayaran_bulan_ini === 'lunas')
                                <span class="badge-status badge-success">Lunas</span>
                            @else
                                <span class="badge-status badge-warning">Belum Lunas</span>
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
            <span class="material-symbols-outlined">group</span>
            <h6>Data tidak ditemukan</h6>
            <p>Ubah filter atau tambah penghuni baru.</p>
        </div>
        @endif
    </div>
</div>
@endsection
