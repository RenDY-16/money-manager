@extends('layouts.app')

@section('title', 'Manajemen Pengeluaran')
@section('subtitle', 'Catat biaya operasional dan kebutuhan kost')

@section('content')
<div class="page-heading">
    <div>
        <h1>Manajemen Pengeluaran</h1>
        <p>Rekam biaya operasional agar laporan keuangan tetap rapi dan akurat.</p>
    </div>
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
                <form action="{{ route('pengeluaran.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                            <label class="form-label mb-0">Kategori</label>
                            <button type="button" class="btn btn-sm btn-link p-0 fw-bold" id="btnKategoriBaru" style="text-decoration:none;">
                                + Tambah kategori
                            </button>
                        </div>
                        <select name="kategori" id="kategoriPengeluaran" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                            <option value="__custom__" {{ old('kategori') === '__custom__' ? 'selected' : '' }}>+ Kategori baru</option>
                        </select>
                    </div>
                    <div class="mb-3" id="kategoriBaruWrap" style="display:none;">
                        <label class="form-label">Nama Kategori Baru</label>
                        <input type="text" name="kategori_baru" id="kategoriBaruInput" class="form-control" value="{{ old('kategori_baru') }}" placeholder="Contoh: Renovasi, Pajak, Keamanan">
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
            <form method="GET" action="{{ route('pengeluaran.index') }}" class="filter-box">
                <input class="compact-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari kategori/keterangan...">
                <select name="kategori" class="compact-input">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                    @endforeach
                </select>
                <input class="compact-input" type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" title="Tanggal mulai">
                <input class="compact-input" type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" title="Tanggal selesai">
                <button type="submit" class="btn-primary-custom">
                    <span class="material-symbols-outlined" style="font-size:18px;">filter_alt</span>
                    Terapkan Filter
                </button>
                <a href="{{ route('pengeluaran.index') }}" class="btn-secondary-custom">Reset</a>
            </form>
            <div class="content-card-body flush">
                @if($pengeluarans->count() > 0)
                    <div class="table-scroll">
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
                        <h6>Data tidak ditemukan</h6>
                        <p>Ubah filter atau catat pengeluaran baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function syncKategoriPengeluaran() {
        const select = document.getElementById('kategoriPengeluaran');
        const wrap = document.getElementById('kategoriBaruWrap');
        const input = document.getElementById('kategoriBaruInput');
        if (!select || !wrap || !input) return;

        const useCustom = select.value === '__custom__';
        wrap.style.display = useCustom ? '' : 'none';
        input.required = useCustom;
        if (useCustom) input.focus();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('kategoriPengeluaran');
        const button = document.getElementById('btnKategoriBaru');
        syncKategoriPengeluaran();
        select?.addEventListener('change', syncKategoriPengeluaran);
        button?.addEventListener('click', () => {
            select.value = '__custom__';
            syncKategoriPengeluaran();
        });
    });
</script>
@endpush
