@extends('layouts.app')

@section('title', 'Data Pengeluaran')
@section('subtitle', 'Catatan semua uang keluar kost')

@section('content')
<div class="summary-card animate-in" style="background: linear-gradient(135deg, #e11d48, #be123c);">
    <div class="summary-icon">
        <i class="bi bi-wallet-fill"></i>
    </div>
    <div>
        <div class="summary-label" style="color: rgba(255,255,255,0.85);">Total Akumulasi Pengeluaran</div>
        <div class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>
</div>

<div class="content-card animate-in">
    <div class="content-card-header">
        <h5><i class="bi bi-graph-down-arrow"></i> Catatan Pengeluaran</h5>
        <a href="{{ route('pengeluaran.create') }}" class="btn-primary-custom" style="background: linear-gradient(135deg, #e11d48, #be123c); box-shadow: 0 2px 8px rgba(225, 29, 72, 0.3);">
            <i class="bi bi-plus-lg"></i> Tambah Pengeluaran
        </a>
    </div>
    <div class="content-card-body">
        @if($pengeluarans->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengeluarans as $i => $pengeluaran)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <span class="badge-status badge-double">
                                {{ $pengeluaran->kategori }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-danger">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</strong>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('pengeluaran.destroy', $pengeluaran) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus catatan pengeluaran ini?')">
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
            <i class="bi bi-wallet-fill"></i>
            <h6>Belum ada data pengeluaran</h6>
            <p>Klik tombol "Tambah Pengeluaran" untuk mencatat pengeluaran baru</p>
        </div>
        @endif
    </div>
</div>
@endsection
