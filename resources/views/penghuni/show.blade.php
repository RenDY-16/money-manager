@extends('layouts.app')

@section('title', 'Detail Penghuni')
@section('subtitle', 'Profil lengkap dan riwayat pembayaran')

@section('content')
<div class="breadcrumb-nav">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('penghuni.index') }}">Data Penghuni</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ $penghuni->nama }}</span>
</div>

<div class="page-heading">
    <div>
        <h1>{{ $penghuni->nama }}</h1>
        <p>Detail informasi penghuni dan riwayat pembayaran kost.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('penghuni.edit', $penghuni) }}" class="btn-primary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
            Edit Penghuni
        </a>
        <a href="{{ route('penghuni.index') }}" class="btn-secondary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Kembali
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4">
        <div class="content-card">
            <div class="content-card-body text-center">
                <div class="avatar mx-auto mb-3" style="width:80px;height:80px;font-size:28px;">
                    {{ strtoupper(substr($penghuni->nama, 0, 2)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $penghuni->nama }}</h5>
                <p class="text-muted mb-3">ID: PGH-{{ str_pad($penghuni->id, 3, '0', STR_PAD_LEFT) }}</p>

                @if($penghuni->tanggal_keluar)
                    <span class="badge-status badge-danger">Sudah Keluar</span>
                @else
                    <span class="badge-status badge-success">Aktif</span>
                @endif

                @if(!$penghuni->tanggal_keluar)
                    @if($statusBayar === 'lunas')
                        <span class="badge-status badge-success ms-1">Lunas</span>
                    @else
                        <span class="badge-status badge-warning ms-1">Belum Lunas</span>
                    @endif
                @endif

                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">No. HP</span>
                        <strong>{{ $penghuni->no_hp }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kamar</span>
                        <strong>
                            @if($penghuni->kamar)
                                {{ $penghuni->kamar->nomor_kamar }} ({{ ucfirst($penghuni->kamar->tipe) }})
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Harga Kamar</span>
                        <strong>Rp {{ number_format(optional($penghuni->kamar)->harga ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tanggal Masuk</span>
                        <strong>{{ $penghuni->tanggal_masuk->locale('id')->translatedFormat('d M Y') }}</strong>
                    </div>
                    @if($penghuni->tanggal_keluar)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tanggal Keluar</span>
                        <strong>{{ $penghuni->tanggal_keluar->locale('id')->translatedFormat('d M Y') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="finance-panel primary mt-4">
            <div class="label"><span class="material-symbols-outlined">payments</span> Total Pembayaran</div>
            <div class="value" style="font-size:20px;">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
            <p class="mb-0 mt-2" style="color:#d8e2ff;font-size:12px;">Akumulasi seluruh pembayaran yang tercatat.</p>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">receipt_long</span> Riwayat Pembayaran</h5>
                <span class="badge-status badge-blue">{{ $riwayatPembayaran->count() }} transaksi</span>
            </div>
            <div class="content-card-body flush">
                @if($riwayatPembayaran->count() > 0)
                <div class="table-scroll">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatPembayaran as $i => $bayar)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $bayar->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge-status {{ $bayar->kategori === 'pembayaran_kost' ? 'badge-blue' : 'badge-warning' }}">
                                        {{ $bayar->kategori === 'pembayaran_kost' ? 'Pembayaran Kost' : 'Lainnya' }}
                                    </span>
                                </td>
                                <td>{{ $bayar->keterangan ?: 'Pembayaran kost' }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <h6>Belum ada riwayat pembayaran</h6>
                    <p>Belum ada transaksi pemasukan yang terkait dengan penghuni ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
