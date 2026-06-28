@extends('layouts.app')

@section('title', 'Data Kamar')
@section('subtitle', 'Kelola semua data kamar kost')

@section('content')
<div class="content-card animate-in">
    <div class="content-card-header">
        <h5><i class="bi bi-door-open-fill"></i> Daftar Kamar</h5>
        <a href="{{ route('kamar.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Kamar
        </a>
    </div>
    <div class="content-card-body">
        @if($kamars->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe</th>
                        <th>Harga / Bulan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kamars as $i => $kamar)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong style="color: var(--navy-900);">{{ $kamar->nomor_kamar }}</strong>
                        </td>
                        <td>
                            <span class="badge-status {{ $kamar->tipe == 'single' ? 'badge-single' : 'badge-double' }}">
                                <i class="bi {{ $kamar->tipe == 'single' ? 'bi-person-fill' : 'bi-people-fill' }}"></i>
                                {{ ucfirst($kamar->tipe) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-status {{ $kamar->status == 'tersedia' ? 'badge-tersedia' : 'badge-terisi' }}">
                                <i class="bi {{ $kamar->status == 'tersedia' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                {{ ucfirst($kamar->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('kamar.edit', $kamar) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('kamar.destroy', $kamar) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
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
            <i class="bi bi-door-open"></i>
            <h6>Belum ada data kamar</h6>
            <p>Klik tombol "Tambah Kamar" untuk menambahkan data kamar baru</p>
        </div>
        @endif
    </div>
</div>
@endsection
