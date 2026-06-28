@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('subtitle', 'Perbarui catatan uang masuk kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--navy-500);"></i> Edit Pemasukan
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

    <form action="{{ route('pemasukan.update', $pemasukan) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Kategori Pemasukan</label>
            <select name="kategori" id="kategoriPemasukan" class="form-select" required>
                @foreach($kategoriPemasukan as $value => $label)
                    <option value="{{ $value }}" {{ old('kategori', $pemasukan->kategori) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" id="penghuniField">
            <label class="form-label">Penghuni Kost</label>
            <select name="penghuni_id" id="penghuniSelect" class="form-select">
                <option value="">-- Pilih Penghuni --</option>
                @foreach($penghunis as $penghuni)
                    <option value="{{ $penghuni->id }}" data-harga="{{ optional($penghuni->kamar)->harga ?? 0 }}" {{ old('penghuni_id', $pemasukan->penghuni_id) == $penghuni->id ? 'selected' : '' }}>
                        {{ $penghuni->nama }} (Kamar {{ $penghuni->kamar ? $penghuni->kamar->nomor_kamar : '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" id="jumlahLabel">Jumlah Pembayaran (Rp)</label>
            <input type="number" name="jumlah" id="jumlahPemasukan" class="form-control" value="{{ old('jumlah', $pemasukan->jumlah) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $pemasukan->tanggal) }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pemasukan->keterangan) }}</textarea>
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Perbarui
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
        const jumlahLabel = document.getElementById('jumlahLabel');
        if (!kategori || !penghuniField || !penghuniSelect) return;

        if (kategori.value === 'pembayaran_kost') {
            penghuniField.style.display = '';
            penghuniSelect.required = true;
            jumlahLabel.textContent = 'Jumlah Pembayaran (Rp)';
        } else {
            penghuniField.style.display = 'none';
            penghuniSelect.required = false;
            penghuniSelect.value = '';
            jumlahLabel.textContent = 'Jumlah Pemasukan Lainnya (Rp)';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncPemasukanForm();
        document.getElementById('kategoriPemasukan')?.addEventListener('change', syncPemasukanForm);
    });
</script>
@endpush
