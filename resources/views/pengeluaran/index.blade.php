@extends('layouts.app')

@section('title', 'Manajemen Pengeluaran')
@section('subtitle', 'Catat biaya operasional dan kebutuhan kost')

@section('content')
<div class="page-heading">
    <div>
        <h1>Manajemen Pengeluaran</h1>
        <p>Rekam biaya operasional agar laporan keuangan tetap rapi dan akurat.</p>
    </div>
    <a href="{{ route('pengeluaran.create') }}" class="btn-secondary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span>
        Form Halaman Penuh
    </a>
</div>

<div class="summary-strip mb-4">
    <div class="finance-panel primary">
        <div class="label"><span class="material-symbols-outlined">account_balance_wallet</span> Total Pengeluaran</div>
        <div class="value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">calendar_month</span> Bulan Ini</div>
        <div class="value text-danger">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">category</span> Kategori</div>
        <div class="value">{{ count($kategoriList) }}</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">edit_note</span> Catat Pengeluaran</h5>
            </div>
            <div class="content-card-body">
                @if($errors->any())
                    <div class="alert-modern" style="background: var(--danger-soft); color: #991b1b; border-color: #fecaca;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('pengeluaran.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pengeluaran</label>
                        <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" placeholder="Contoh: 250000" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran listrik">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">receipt_long</span> Riwayat Pengeluaran</h5>
                <span class="badge-status badge-danger">{{ $pengeluarans->count() }} transaksi</span>
            </div>
            <div class="content-card-body flush">
                @if($pengeluarans->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengeluarans as $pengeluaran)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td><span class="badge-status badge-blue">{{ $pengeluaran->kategori }}</span></td>
                                    <td>{{ $pengeluaran->keterangan ?: '-' }}</td>
                                    <td class="text-end fw-bold text-danger">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                    <td><span class="badge-status badge-warning">Tercatat</span></td>
                                    <td>
                                        <div class="action-buttons justify-content-end">
                                            <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn-action btn-edit" title="Edit">
                                                <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                            </a>
                                            <form action="{{ route('pengeluaran.destroy', $pengeluaran) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
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
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                        <h6>Belum ada pengeluaran</h6>
                        <p>Gunakan form di sebelah kiri untuk mencatat biaya operasional.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
