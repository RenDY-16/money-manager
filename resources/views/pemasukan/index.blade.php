@extends('layouts.app')

@section('title', 'Manajemen Pemasukan')
@section('subtitle', 'Catat pembayaran dan riwayat pemasukan kost')

@section('content')
<div class="page-heading">
    <div>
        <h1>Manajemen Pemasukan</h1>
        <p>Catat pembayaran penghuni dan pantau pemasukan yang sudah masuk.</p>
    </div>
    <a href="{{ route('pemasukan.create') }}" class="btn-secondary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span>
        Form Halaman Penuh
    </a>
</div>

<div class="summary-strip mb-4">
    <div class="finance-panel primary">
        <div class="label"><span class="material-symbols-outlined">payments</span> Total Pemasukan</div>
        <div class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">calendar_month</span> Bulan Ini</div>
        <div class="value text-success">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">group</span> Penghuni Aktif</div>
        <div class="value">{{ $penghunis->count() }} Orang</div>
    </div>
</div>

<div class="content-card mb-4">
    <div class="content-card-header">
        <h5><span class="material-symbols-outlined">mark_chat_unread</span> Pengingat WhatsApp Belum Bayar</h5>
        <span class="badge-status badge-warning">{{ $periodeTagihan }}</span>
    </div>
    <div class="content-card-body flush">
        @if($penghuniBelumBayar->count() > 0)
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>No. WhatsApp</th>
                            <th>Kamar</th>
                            <th class="text-end">Tagihan</th>
                            <th>Template Pesan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penghuniBelumBayar as $penghuni)
                            <tr>
                                <td>
                                    <div class="table-title">
                                        <span class="row-avatar">{{ strtoupper(substr($penghuni->nama, 0, 2)) }}</span>
                                        {{ $penghuni->nama }}
                                    </div>
                                </td>
                                <td>{{ $penghuni->no_hp }}</td>
                                <td>
                                    @if($penghuni->kamar)
                                        <span class="badge-status badge-blue">Kamar {{ $penghuni->kamar->nomor_kamar }}</span>
                                    @else
                                        <span class="badge-status badge-neutral">Kamar Terhapus</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format(optional($penghuni->kamar)->harga ?? 0, 0, ',', '.') }}</td>
                                <td style="min-width:260px; max-width:360px;">
                                    <div class="small text-muted">{{ $penghuni->wa_message }}</div>
                                </td>
                                <td>
                                    <div class="action-buttons justify-content-end">
                                        @if($penghuni->wa_link)
                                            <a href="{{ $penghuni->wa_link }}" target="_blank" class="btn-primary-custom" style="min-height:34px;padding:8px 12px;">
                                                <i class="bi bi-whatsapp"></i> Chat
                                            </a>
                                        @else
                                            <span class="badge-status badge-danger">Nomor tidak valid</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-4">
                <span class="material-symbols-outlined">check_circle</span>
                <h6>Semua penghuni aktif sudah membayar</h6>
                <p>Tidak ada pengingat WhatsApp untuk periode {{ $periodeTagihan }}.</p>
            </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">add_card</span> Catat Pembayaran</h5>
            </div>
            <div class="content-card-body">
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

                <form action="{{ route('pemasukan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Penghuni</label>
                        <select name="penghuni_id" class="form-select" required>
                            <option value="">Pilih penghuni</option>
                            @foreach($penghunis as $penghuni)
                                <option value="{{ $penghuni->id }}" {{ old('penghuni_id') == $penghuni->id ? 'selected' : '' }}>
                                    {{ $penghuni->nama }} @if($penghuni->kamar) - Kamar {{ $penghuni->kamar->nomor_kamar }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" placeholder="Contoh: 850000" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran bulan ini">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100" {{ $penghunis->isEmpty() ? 'disabled' : '' }}>
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Simpan Transaksi
                    </button>
                    @if($penghunis->isEmpty())
                        <div class="text-danger small fw-semibold mt-3">Tidak ada penghuni aktif. Tambahkan penghuni terlebih dahulu.</div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">receipt_long</span> Riwayat Pemasukan</h5>
                <span class="badge-status badge-blue">{{ $pemasukans->count() }} transaksi</span>
            </div>
            <div class="content-card-body flush">
                @if($pemasukans->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Penghuni</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemasukans as $pemasukan)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pemasukan->tanggal)->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <div class="table-title">
                                            <span class="row-avatar">{{ strtoupper(substr(optional($pemasukan->penghuni)->nama ?? 'PT', 0, 2)) }}</span>
                                            {{ optional($pemasukan->penghuni)->nama ?? 'Penghuni terhapus' }}
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $pemasukan->keterangan ?: '-' }}</td>
                                    <td><span class="badge-status badge-success">Berhasil</span></td>
                                    <td>
                                        <div class="action-buttons justify-content-end">
                                            <a href="{{ route('pemasukan.edit', $pemasukan) }}" class="btn-action btn-edit" title="Edit">
                                                <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                            </a>
                                            <form action="{{ route('pemasukan.destroy', $pemasukan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pemasukan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                    <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
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
                        <h6>Belum ada pemasukan</h6>
                        <p>Gunakan form di sebelah kiri untuk mencatat pembayaran pertama.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
