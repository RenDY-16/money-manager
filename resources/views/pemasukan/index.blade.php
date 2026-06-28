@extends('layouts.app')

@section('title', 'Manajemen Pemasukan')
@section('subtitle', 'Catat pembayaran dan riwayat pemasukan kost')

@section('content')
<div class="page-heading">
    <div>
        <h1>Manajemen Pemasukan</h1>
        <p>Catat pembayaran penghuni, pemasukan lain, dan pantau status lunas bulan berjalan.</p>
    </div>
</div>

<div class="metric-grid metric-grid-5 mb-4">
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
        <div class="value">{{ $jumlahPenghuniAktif }} Orang</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">check_circle</span> Lunas</div>
        <div class="value text-success">{{ $jumlahLunas }} Orang</div>
    </div>
    <div class="finance-panel">
        <div class="label"><span class="material-symbols-outlined">pending_actions</span> Belum Lunas</div>
        <div class="value text-danger">{{ $jumlahBelumLunas }} Orang</div>
    </div>
</div>

<div class="content-card mb-4">
    <div class="content-card-header">
        <h5><span class="material-symbols-outlined">mark_chat_unread</span> Pengingat WhatsApp Belum Bayar</h5>
        <span class="badge-status badge-warning">{{ $periodeTagihan }}</span>
    </div>
    <div class="content-card-body flush">
        @if($penghuniBelumBayar->count() > 0)
            <div class="table-scroll">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>No. WhatsApp</th>
                            <th>Kamar</th>
                            <th class="text-end">Tagihan</th>
                            <th>Status</th>
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
                                <td><span class="badge-status badge-warning">Belum Lunas</span></td>
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
                <h5><span class="material-symbols-outlined">add_card</span> Catat Pemasukan</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('pemasukan.store') }}" method="POST" id="pemasukanForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kategori Pemasukan</label>
                        <select name="kategori" id="kategoriPemasukan" class="form-select" required>
                            @foreach($kategoriPemasukan as $value => $label)
                                <option value="{{ $value }}" {{ old('kategori', 'pembayaran_kost') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="penghuniField">
                        <label class="form-label">Penghuni</label>
                        <select name="penghuni_id" id="penghuniSelect" class="form-select">
                            <option value="">Pilih penghuni</option>
                            @foreach($penghunis as $penghuni)
                                <option value="{{ $penghuni->id }}" data-harga="{{ optional($penghuni->kamar)->harga ?? 0 }}" {{ old('penghuni_id') == $penghuni->id ? 'selected' : '' }}>
                                    {{ $penghuni->nama }} @if($penghuni->kamar) - Kamar {{ $penghuni->kamar->nomor_kamar }} @endif
                                </option>
                            @endforeach
                        </select>
                        @if($penghunis->isEmpty())
                            <div class="form-text text-danger">Belum ada penghuni aktif untuk kategori pembayaran kost.</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="jumlahLabel">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah" id="jumlahPemasukan" class="form-control" value="{{ old('jumlah') }}" placeholder="Pilih penghuni agar jumlah terisi otomatis" required>
                        <div class="form-text text-muted" id="jumlahHelp">Untuk pembayaran kost, jumlah akan mengikuti harga kamar penghuni yang dipilih.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran bulan ini atau pemasukan laundry">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Simpan Transaksi
                    </button>
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
            <form method="GET" action="{{ route('pemasukan.index') }}" class="filter-box">
                <input class="compact-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari penghuni/keterangan...">
                <select name="kategori" class="compact-input">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriPemasukan as $value => $label)
                        <option value="{{ $value }}" {{ request('kategori') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input class="compact-input" type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" title="Tanggal mulai">
                <input class="compact-input" type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" title="Tanggal selesai">
                <button type="submit" class="btn-primary-custom">
                    <span class="material-symbols-outlined" style="font-size:18px;">filter_alt</span>
                    Terapkan Filter
                </button>
                <a href="{{ route('pemasukan.index') }}" class="btn-secondary-custom">Reset</a>
            </form>
            <div class="content-card-body flush">
                @if($pemasukans->count() > 0)
                    <div class="table-scroll">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
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
                                        <span class="badge-status {{ $pemasukan->kategori === 'pembayaran_kost' ? 'badge-blue' : 'badge-warning' }}">
                                            {{ $kategoriPemasukan[$pemasukan->kategori] ?? ucfirst(str_replace('_', ' ', $pemasukan->kategori)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-title">
                                            <span class="row-avatar">{{ strtoupper(substr(optional($pemasukan->penghuni)->nama ?? 'PL', 0, 2)) }}</span>
                                            <div>
                                                {{ optional($pemasukan->penghuni)->nama ?? 'Pemasukan lainnya' }}
                                                @if(optional($pemasukan->penghuni)->kamar)
                                                    <div class="cell-muted">Kamar {{ $pemasukan->penghuni->kamar->nomor_kamar }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $pemasukan->keterangan ?: '-' }}</td>
                                    <td><span class="badge-status badge-success">Lunas/Tercatat</span></td>
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
                        <h6>Data tidak ditemukan</h6>
                        <p>Ubah filter atau catat pemasukan baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function syncPemasukanForm() {
        const kategori = document.getElementById('kategoriPemasukan');
        const penghuniField = document.getElementById('penghuniField');
        const penghuniSelect = document.getElementById('penghuniSelect');
        const jumlahInput = document.getElementById('jumlahPemasukan');
        const jumlahLabel = document.getElementById('jumlahLabel');
        const jumlahHelp = document.getElementById('jumlahHelp');

        if (!kategori || !penghuniField || !penghuniSelect || !jumlahInput) return;

        if (kategori.value === 'pembayaran_kost') {
            penghuniField.style.display = '';
            penghuniSelect.required = true;
            jumlahLabel.textContent = 'Jumlah Pembayaran';
            jumlahInput.placeholder = 'Pilih penghuni agar jumlah terisi otomatis';
            jumlahHelp.textContent = 'Untuk pembayaran kost, jumlah akan mengikuti harga kamar penghuni yang dipilih.';
            const selected = penghuniSelect.options[penghuniSelect.selectedIndex];
            const harga = selected ? selected.dataset.harga : '';
            if (harga && Number(harga) > 0) {
                jumlahInput.value = Math.round(Number(harga));
            }
        } else {
            penghuniField.style.display = 'none';
            penghuniSelect.required = false;
            penghuniSelect.value = '';
            jumlahLabel.textContent = 'Jumlah Pemasukan Lainnya';
            jumlahInput.placeholder = 'Contoh: 250000';
            jumlahHelp.textContent = 'Isi manual untuk pemasukan selain pembayaran kost.';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncPemasukanForm();
        document.getElementById('kategoriPemasukan')?.addEventListener('change', syncPemasukanForm);
        document.getElementById('penghuniSelect')?.addEventListener('change', syncPemasukanForm);
    });
</script>
@endpush
