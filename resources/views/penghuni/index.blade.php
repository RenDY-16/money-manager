@extends('layouts.app')

@section('title', 'Data Penghuni')
@section('subtitle', 'Kelola semua data penghuni kost')

@section('content')
<div class="content-card animate-in">
    <div class="content-card-header">
        <h5><i class="bi bi-people-fill"></i> Daftar Penghuni</h5>
        <a href="{{ route('penghuni.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Penghuni
        </a>
    </div>
    <div class="content-card-body">
        @if($penghunis->count() > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Kamar</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Keluar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penghunis as $i => $penghuni)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong style="color: var(--navy-900);">{{ $penghuni->nama }}</strong>
                        </td>
                        <td>{{ $penghuni->no_hp }}</td>
                        <td>
                            @if($penghuni->kamar)
                            <span class="badge-status badge-single">
                                Kamar {{ $penghuni->kamar->nomor_kamar }}
                            </span>
                            @else
                            <span class="text-muted">Kamar Terhapus</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($penghuni->tanggal_masuk)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>
                            @if($penghuni->tanggal_keluar)
                                {{ \Carbon\Carbon::parse($penghuni->tanggal_keluar)->locale('id')->translatedFormat('d F Y') }}
                            @else
                                <span class="badge-status badge-tersedia">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('penghuni.edit', $penghuni) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('penghuni.destroy', $penghuni) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus penghuni ini? Status kamar akan dikembalikan menjadi tersedia.')">
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
            <i class="bi bi-people"></i>
            <h6>Belum ada data penghuni</h6>
            <p>Klik tombol "Tambah Penghuni" untuk mendaftarkan penghuni kost baru</p>
        </div>
        @endif
    </div>
</div>
@endsection
