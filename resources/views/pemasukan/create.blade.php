@extends('layouts.app')

@section('title', 'Tambah Pemasukan')
@section('subtitle', 'Catat pembayaran dari penghuni kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-plus-circle-fill" style="color: var(--navy-500);"></i> Form Tambah Pemasukan
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

    <form action="{{ route('pemasukan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Penghuni Kost</label>
            <select name="penghuni_id" class="form-select" required>
                <option value="">-- Pilih Penghuni --</option>
                @foreach($penghunis as $penghuni)
                    <option value="{{ $penghuni->id }}" {{ old('penghuni_id') == $penghuni->id ? 'selected' : '' }}>
                        {{ $penghuni->nama }} (Kamar {{ $penghuni->kamar ? $penghuni->kamar->nomor_kamar : '-' }})
                    </option>
                @endforeach
            </select>
            @if($penghunis->isEmpty())
                <div class="form-text text-danger">Tidak ada penghuni aktif. Silakan tambahkan penghuni terlebih dahulu.</div>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Pembayaran (Rp)</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" placeholder="Contoh: 500000" required>
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
            <button type="submit" class="btn-submit" {{ $penghunis->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-check-lg"></i> Simpan
            </button>
            <a href="{{ route('pemasukan.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
