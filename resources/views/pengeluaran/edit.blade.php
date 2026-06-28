@extends('layouts.app')

@section('title', 'Edit Pengeluaran')
@section('subtitle', 'Perbarui catatan uang keluar kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--accent-rose);"></i> Edit Pengeluaran
    </h5>

    @if($errors->any())
    <div class="alert-modern" style="background: #fff1f2; color: #9d174d; margin-bottom: 20px;">
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
            <label class="form-label">Kategori Pengeluaran</label>
            <select name="kategori" class="form-select" required>
                <option value="Listrik" {{ old('kategori', $pengeluaran->kategori) == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                <option value="Air" {{ old('kategori', $pengeluaran->kategori) == 'Air' ? 'selected' : '' }}>Air</option>
                <option value="Kebersihan" {{ old('kategori', $pengeluaran->kategori) == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                <option value="Perbaikan" {{ old('kategori', $pengeluaran->kategori) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan & Perawatan</option>
                <option value="Lainnya" {{ old('kategori', $pengeluaran->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
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
