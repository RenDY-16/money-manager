@extends('layouts.app')

@section('title', 'Recycle Bin')
@section('subtitle', 'Pulihkan atau hapus permanen data yang sudah dihapus')

@section('content')
<div class="page-heading">
    <div>
        <h1>Recycle Bin</h1>
        <p>Data yang dihapus tersimpan di sini. Anda bisa memulihkan atau menghapus permanen.</p>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-card {{ $tab === 'kamar' ? 'card-hover' : '' }}" style="cursor:pointer;" onclick="window.location='{{ route('recycle-bin.index', ['tab' => 'kamar']) }}'">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon">bed</span></div>
        <div class="metric-label">Kamar Terhapus</div>
        <div class="metric-value">{{ $counts['kamar'] }}</div>
    </div>
    <div class="metric-card {{ $tab === 'penghuni' ? 'card-hover' : '' }}" style="cursor:pointer;" onclick="window.location='{{ route('recycle-bin.index', ['tab' => 'penghuni']) }}'">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon danger">group</span></div>
        <div class="metric-label">Penghuni Terhapus</div>
        <div class="metric-value">{{ $counts['penghuni'] }}</div>
    </div>
    <div class="metric-card {{ $tab === 'pemasukan' ? 'card-hover' : '' }}" style="cursor:pointer;" onclick="window.location='{{ route('recycle-bin.index', ['tab' => 'pemasukan']) }}'">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon success">payments</span></div>
        <div class="metric-label">Pemasukan Terhapus</div>
        <div class="metric-value">{{ $counts['pemasukan'] }}</div>
    </div>
    <div class="metric-card {{ $tab === 'pengeluaran' ? 'card-hover' : '' }}" style="cursor:pointer;" onclick="window.location='{{ route('recycle-bin.index', ['tab' => 'pengeluaran']) }}'">
        <div class="metric-top"><span class="material-symbols-outlined metric-icon warning">account_balance_wallet</span></div>
        <div class="metric-label">Pengeluaran Terhapus</div>
        <div class="metric-value">{{ $counts['pengeluaran'] }}</div>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5>
            <span class="material-symbols-outlined">delete_sweep</span>
            Data {{ ucfirst($tab) }} Terhapus
        </h5>
        <div class="d-flex gap-2 flex-wrap">
            @foreach(['kamar', 'penghuni', 'pemasukan', 'pengeluaran'] as $t)
                <a href="{{ route('recycle-bin.index', ['tab' => $t]) }}"
                   class="{{ $tab === $t ? 'btn-primary-custom' : 'btn-secondary-custom' }}"
                   style="min-height:34px;padding:8px 14px;font-size:12px;">
                    {{ ucfirst($t) }} ({{ $counts[$t] }})
                </a>
            @endforeach
        </div>
    </div>
    <div class="content-card-body flush">
        @if($tab === 'kamar')
            @if($deletedKamar->count() > 0)
            <div class="table-scroll">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nomor Kamar</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Dihapus</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedKamar as $kamar)
                        <tr>
                            <td class="fw-bold">Kamar {{ $kamar->nomor_kamar }}</td>
                            <td><span class="badge-status badge-blue">{{ ucfirst($kamar->tipe) }}</span></td>
                            <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $kamar->deleted_at->locale('id')->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <div class="action-buttons justify-content-end">
                                    <form action="{{ route('recycle-bin.restore', ['type' => 'kamar', 'id' => $kamar->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-edit" title="Pulihkan">
                                            <span class="material-symbols-outlined" style="font-size:18px;">restore</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('recycle-bin.force-delete', ['type' => 'kamar', 'id' => $kamar->id]) }}" method="POST" onsubmit="return confirmDelete(this, 'Data kamar akan dihapus permanen dan tidak bisa dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Permanen">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete_forever</span>
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
                <span class="material-symbols-outlined">bed</span>
                <h6>Tidak ada kamar terhapus</h6>
                <p>Semua data kamar masih aktif dalam sistem.</p>
            </div>
            @endif
        @endif

        @if($tab === 'penghuni')
            @if($deletedPenghuni->count() > 0)
            <div class="table-scroll">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Kamar</th>
                            <th>Dihapus</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedPenghuni as $penghuni)
                        <tr>
                            <td class="fw-bold">{{ $penghuni->nama }}</td>
                            <td>{{ $penghuni->no_hp }}</td>
                            <td>
                                @if($penghuni->kamar)
                                    <span class="badge-status badge-blue">Kamar {{ $penghuni->kamar->nomor_kamar }}</span>
                                @else
                                    <span class="badge-status badge-neutral">-</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $penghuni->deleted_at->locale('id')->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <div class="action-buttons justify-content-end">
                                    <form action="{{ route('recycle-bin.restore', ['type' => 'penghuni', 'id' => $penghuni->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-edit" title="Pulihkan">
                                            <span class="material-symbols-outlined" style="font-size:18px;">restore</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('recycle-bin.force-delete', ['type' => 'penghuni', 'id' => $penghuni->id]) }}" method="POST" onsubmit="return confirmDelete(this, 'Data penghuni akan dihapus permanen.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Permanen">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete_forever</span>
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
                <span class="material-symbols-outlined">group</span>
                <h6>Tidak ada penghuni terhapus</h6>
                <p>Semua data penghuni masih aktif dalam sistem.</p>
            </div>
            @endif
        @endif

        @if($tab === 'pemasukan')
            @if($deletedPemasukan->count() > 0)
            <div class="table-scroll">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Penghuni</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Dihapus</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedPemasukan as $pemasukan)
                        <tr>
                            <td>{{ $pemasukan->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                            <td>{{ optional($pemasukan->penghuni)->nama ?? 'Lainnya' }}</td>
                            <td>{{ $pemasukan->keterangan ?: '-' }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $pemasukan->deleted_at->locale('id')->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <div class="action-buttons justify-content-end">
                                    <form action="{{ route('recycle-bin.restore', ['type' => 'pemasukan', 'id' => $pemasukan->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-edit" title="Pulihkan">
                                            <span class="material-symbols-outlined" style="font-size:18px;">restore</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('recycle-bin.force-delete', ['type' => 'pemasukan', 'id' => $pemasukan->id]) }}" method="POST" onsubmit="return confirmDelete(this, 'Data pemasukan akan dihapus permanen.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Permanen">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete_forever</span>
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
                <span class="material-symbols-outlined">payments</span>
                <h6>Tidak ada pemasukan terhapus</h6>
                <p>Semua data pemasukan masih aktif dalam sistem.</p>
            </div>
            @endif
        @endif

        @if($tab === 'pengeluaran')
            @if($deletedPengeluaran->count() > 0)
            <div class="table-scroll">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Dihapus</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedPengeluaran as $pengeluaran)
                        <tr>
                            <td>{{ $pengeluaran->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                            <td><span class="badge-status badge-blue">{{ $pengeluaran->kategori }}</span></td>
                            <td>{{ $pengeluaran->keterangan ?: '-' }}</td>
                            <td class="text-end fw-bold text-danger">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $pengeluaran->deleted_at->locale('id')->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <div class="action-buttons justify-content-end">
                                    <form action="{{ route('recycle-bin.restore', ['type' => 'pengeluaran', 'id' => $pengeluaran->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-edit" title="Pulihkan">
                                            <span class="material-symbols-outlined" style="font-size:18px;">restore</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('recycle-bin.force-delete', ['type' => 'pengeluaran', 'id' => $pengeluaran->id]) }}" method="POST" onsubmit="return confirmDelete(this, 'Data pengeluaran akan dihapus permanen.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Permanen">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete_forever</span>
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
                <span class="material-symbols-outlined">account_balance_wallet</span>
                <h6>Tidak ada pengeluaran terhapus</h6>
                <p>Semua data pengeluaran masih aktif dalam sistem.</p>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
