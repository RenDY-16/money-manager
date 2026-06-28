@extends('layouts.app')

@section('title', 'Edit Penghuni')
@section('subtitle', 'Perbarui data penghuni kost')

@section('content')
<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--navy-500);"></i> Edit Penghuni: {{ $penghuni->nama }}
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

    <form action="{{ route('penghuni.update', $penghuni) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $penghuni->nama) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $penghuni->no_hp) }}" inputmode="numeric" pattern="[0-9]+" required oninvalid="this.setCustomValidity('Nomor telepon hanya boleh berisi angka.')" oninput="this.setCustomValidity('')">
        </div>
        <div class="mb-3">
            <label class="form-label">Kamar</label>
            <select name="kamar_id" class="form-select" required>
                @foreach($kamars as $kamar)
                    <option value="{{ $kamar->id }}" {{ old('kamar_id', $penghuni->kamar_id) == $kamar->id ? 'selected' : '' }}>
                        Kamar {{ $kamar->nomor_kamar }} ({{ ucfirst($kamar->tipe) }} - Status: {{ ucfirst($kamar->status) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $penghuni->tanggal_masuk) }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Tanggal Keluar (Kosongkan jika masih aktif)</label>
            <input type="date" name="tanggal_keluar" class="form-control" value="{{ old('tanggal_keluar', $penghuni->tanggal_keluar) }}">
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Perbarui
            </button>
            <a href="{{ route('penghuni.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const phoneInput = document.querySelector('input[name="no_hp"]');

        if (!form || !phoneInput) return;

        form.addEventListener('submit', (event) => {
            const phone = phoneInput.value.trim();
            if (!/^[0-9]+$/.test(phone)) {
                event.preventDefault();
                alert('Nomor telepon hanya boleh berisi angka. Contoh: 081234567890');
                phoneInput.focus();
            }
        });
    });
</script>
@endpush
