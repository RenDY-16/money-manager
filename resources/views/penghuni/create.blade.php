@extends('layouts.app')

@section('title', 'Tambah Penghuni')
@section('subtitle', 'Daftarkan penghuni kost baru')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-person-plus-fill" style="color: var(--navy-500);"></i> Form Tambah Penghuni
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

    <form action="{{ route('penghuni.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: John Doe" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Pilih Kamar (Tersedia)</label>
            <select name="kamar_id" class="form-select" required>
                <option value="">-- Pilih Kamar --</option>
                @foreach($kamars as $kamar)
                    <option value="{{ $kamar->id }}" {{ old('kamar_id') == $kamar->id ? 'selected' : '' }}>
                        Kamar {{ $kamar->nomor_kamar }} ({{ ucfirst($kamar->tipe) }} - Rp {{ number_format($kamar->harga, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
            @if($kamars->isEmpty())
                <div class="form-text text-danger">Tidak ada kamar tersedia. Silakan tambah kamar baru atau kosongkan kamar terlebih dahulu.</div>
            @endif
        </div>
        <div class="mb-4">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit" {{ $kamars->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-check-lg"></i> Simpan
            </button>
            <a href="{{ route('penghuni.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
