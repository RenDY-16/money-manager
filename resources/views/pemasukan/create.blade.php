@extends('layouts.app')

@section('title', 'Tambah Pemasukan')
@section('subtitle', 'Catat pembayaran kost atau pemasukan lainnya')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-plus-circle-fill" style="color: var(--navy-500);"></i> Form Tambah Pemasukan
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

    <form action="{{ route('pemasukan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kategori Pemasukan</label>
            <select name="kategori" id="kategoriPemasukan" class="form-select" required>
                @foreach($kategoriPemasukan as $value => $label)
                    <option value="{{ $value }}" {{ old('kategori', 'pembayaran_kost') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" id="penghuniField">
            <label class="form-label">Penghuni Kost</label>
            <select name="penghuni_id" id="penghuniSelect" class="form-select">
                <option value="">-- Pilih Penghuni --</option>
                @foreach($penghunis as $penghuni)
                    <option value="{{ $penghuni->id }}" data-harga="{{ optional($penghuni->kamar)->harga ?? 0 }}" {{ old('penghuni_id') == $penghuni->id ? 'selected' : '' }}>
                        {{ $penghuni->nama }} (Kamar {{ $penghuni->kamar ? $penghuni->kamar->nomor_kamar : '-' }})
                    </option>
                @endforeach
            </select>
            @if($penghunis->isEmpty())
                <div class="form-text text-danger">Tidak ada penghuni aktif. Silakan tambahkan penghuni terlebih dahulu.</div>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label" id="jumlahLabel">Jumlah Pembayaran (Rp)</label>
            <input type="number" name="jumlah" id="jumlahPemasukan" class="form-control" value="{{ old('jumlah') }}" placeholder="Pilih penghuni agar jumlah terisi otomatis">
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran Kost Bulan Juni 2026">{{ old('keterangan') }}</textarea>
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Simpan
            </button>
            <a href="{{ route('pemasukan.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function syncPemasukanForm() {
        const kategori = document.getElementById('kategoriPemasukan');
        const penghuniField = document.getElementById('penghuniField');
        const penghuniSelect = document.getElementById('penghuniSelect');
        const jumlahInput = document.getElementById('jumlahPemasukan');
        const jumlahLabel = document.getElementById('jumlahLabel');
        if (!kategori || !penghuniField || !penghuniSelect || !jumlahInput) return;

        if (kategori.value === 'pembayaran_kost') {
            penghuniField.style.display = '';
            penghuniSelect.required = true;
            jumlahLabel.textContent = 'Jumlah Pembayaran (Rp)';
            jumlahInput.placeholder = 'Pilih penghuni agar jumlah terisi otomatis';
            const selected = penghuniSelect.options[penghuniSelect.selectedIndex];
            const harga = selected ? selected.dataset.harga : '';
            if (harga && Number(harga) > 0) {
                jumlahInput.value = Math.round(Number(harga));
            }
        } else {
            penghuniField.style.display = 'none';
            penghuniSelect.required = false;
            penghuniSelect.value = '';
            jumlahLabel.textContent = 'Jumlah Pemasukan Lainnya (Rp)';
            jumlahInput.placeholder = 'Contoh: 250000';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncPemasukanForm();
        document.getElementById('kategoriPemasukan')?.addEventListener('change', syncPemasukanForm);
        document.getElementById('penghuniSelect')?.addEventListener('change', syncPemasukanForm);
    });
</script>
@endpush
