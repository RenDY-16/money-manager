@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('subtitle', 'Perbarui catatan uang masuk kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--navy-500);"></i> Edit Pemasukan
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

    <form action="{{ route('pemasukan.update', $pemasukan) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Penghuni Kost</label>
            <select name="penghuni_id" class="form-select" required>
                @foreach($penghunis as $penghuni)
                    <option value="{{ $penghuni->id }}" {{ old('penghuni_id', $pemasukan->penghuni_id) == $penghuni->id ? 'selected' : '' }}>
                        {{ $penghuni->nama }} (Kamar {{ $penghuni->kamar ? $penghuni->kamar->nomor_kamar : '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Pembayaran (Rp)</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $pemasukan->jumlah) }}" required>
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
