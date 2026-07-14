@extends('layouts.app')

@section('title', 'Edit Kamar')
@section('subtitle', 'Perbarui data kamar')

@section('content')
<div class="breadcrumb-nav">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('kamar.index') }}">Manajemen Kamar</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Edit Kamar {{ $kamar->nomor_kamar }}</span>
</div>

<div class="form-card animate-in">
    <h5 style="font-size: 18px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="bi bi-pencil-square" style="color: var(--navy-500);"></i> Edit Kamar {{ $kamar->nomor_kamar }}
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

    <form action="{{ route('kamar.update', $kamar) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nomor Kamar</label>
            <input type="text" name="nomor_kamar" class="form-control" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipe Kamar</label>
            <select name="tipe" class="form-select" required>
                <option value="single" {{ old('tipe', $kamar->tipe) == 'single' ? 'selected' : '' }}>Single</option>
                <option value="double" {{ old('tipe', $kamar->tipe) == 'double' ? 'selected' : '' }}>Double</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga / Bulan (Rp)</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $kamar->harga) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="tersedia" {{ old('status', $kamar->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="terisi" {{ old('status', $kamar->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Foto Kamar</label>
            @if($kamar->foto)
                <div class="mb-2">
                    <img src="{{ asset($kamar->foto) }}" alt="Foto Kamar {{ $kamar->nomor_kamar }}" style="max-width:200px; border-radius:8px; border:1px solid var(--border);">
                    <div class="form-text text-muted mt-1">Foto saat ini. Upload baru untuk mengganti.</div>
                </div>
            @endif
            <input type="file" name="foto" class="form-control" accept="image/png,image/jpeg,image/webp" id="fotoInput">
            <div class="form-text text-muted">Format jpg, jpeg, png, atau webp. Maksimal 2 MB.</div>
            <img id="fotoPreview" src="" alt="Preview" style="display:none; margin-top:12px; max-width:200px; border-radius:8px; border:1px solid var(--border);">
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Perbarui
            </button>
            <a href="{{ route('kamar.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('fotoPreview');
        if (file && preview) {
            const reader = new FileReader();
            reader.onload = (ev) => { preview.src = ev.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
