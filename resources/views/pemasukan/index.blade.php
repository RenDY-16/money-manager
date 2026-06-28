@extends('layouts.app')

@section('title', 'Data Pemasukan')
@section('subtitle', 'Catatan semua uang masuk kost')

@section('content')
<div class="summary-card animate-in">
    <div class="summary-icon">
        <i class="bi bi-wallet2"></i>
    </div>
    <div>
        <div class="summary-label">Total Akumulasi Pemasukan</div>
        <div class="summary-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
    </div>
</div>

<div class="content-card animate-in">
    <div class="content-card-header">
        <h5><i class="bi bi-graph-up-arrow"></i> Catatan Pemasukan</h5>
        <a href="{{ route('pemasukan.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Pemasukan
        </a>
    </div>
    <div class="content-card-body">
        @if($pemasukans->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penghuni</th>
                        <th>Kamar</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemasukans as $i => $pemasukan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($pemasukan->penghuni)
                            <strong style="color: var(--navy-900);">{{ $pemasukan->penghuni->nama }}</strong>
                            @else
                            <span class="text-muted">Penghuni Terhapus</span>
                            @endif
                        </td>
                        <td>
                            @if($pemasukan->penghuni && $pemasukan->penghuni->kamar)
                            <span class="badge-status badge-single">
                                Kamar {{ $pemasukan->penghuni->kamar->nomor_kamar }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">+ Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</strong>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pemasukan->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ $pemasukan->keterangan ?? '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('pemasukan.edit', $pemasukan) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('pemasukan.destroy', $pemasukan) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus catatan pemasukan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
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
            <i class="bi bi-wallet2"></i>
            <h6>Belum ada data pemasukan</h6>
            <p>Klik tombol "Tambah Pemasukan" untuk mencatat pemasukan baru</p>
        </div>
        @endif
    </div>
</div>
@endsection
