@extends('layouts.app')

@section('title', 'Edit Pengeluaran')
@section('subtitle', 'Perbarui catatan uang keluar kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--accent-rose);"></i> Edit Pengeluaran
    </h5>

    @if($errors->any())
    <div class="alert-modern alert-danger-modern">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <form action="{{ route('pengeluaran.update', $pengeluaran) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                <label class="form-label mb-0">Kategori Pengeluaran</label>
                <button type="button" class="btn btn-sm btn-link p-0 fw-bold" id="btnKategoriBaru" style="text-decoration:none;">+ Tambah kategori</button>
            </div>
            <select name="kategori" id="kategoriPengeluaran" class="form-select" required>
                <option value="">Pilih kategori</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori }}" {{ old('kategori', $pengeluaran->kategori) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                @endforeach
                <option value="__custom__" {{ old('kategori') === '__custom__' ? 'selected' : '' }}>+ Kategori baru</option>
            </select>
        </div>
        <div class="mb-3" id="kategoriBaruWrap" style="display:none;">
            <label class="form-label">Nama Kategori Baru</label>
            <input type="text" name="kategori_baru" id="kategoriBaruInput" class="form-control" value="{{ old('kategori_baru') }}" placeholder="Contoh: Renovasi, Pajak, Keamanan">
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Pengeluaran (Rp)</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $pengeluaran->jumlah) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pengeluaran</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #e11d48, #be123c); box-shadow: 0 2px 8px rgba(225, 29, 72, 0.3);">
                <i class="bi bi-check-lg"></i> Perbarui
            </button>
            <a href="{{ route('pengeluaran.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
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
