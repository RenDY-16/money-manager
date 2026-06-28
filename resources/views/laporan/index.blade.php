@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('subtitle', 'Analisis pemasukan, pengeluaran, dan saldo bersih')

@section('content')
<div class="page-heading no-print">
    <div>
        <h1>Laporan Keuangan</h1>
        <p>Ringkasan keuangan berdasarkan filter: <strong>{{ $filterLabel }}</strong>.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('laporan.export.excel', request()->query()) }}" class="btn-secondary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">table_view</span>
            Export Excel
        </a>
        <button type="button" onclick="printCleanReport()" class="btn-primary-custom">
            <span class="material-symbols-outlined" style="font-size:18px;">print</span>
            Cetak Laporan
        </button>
    </div>
</div>

<form method="GET" action="{{ route('laporan.index') }}" class="filter-box mb-4 rounded no-print" style="border:1px solid var(--border);">
    <select name="bulan" class="compact-input">
        <option value="semua" {{ $selectedMonth === 'semua' ? 'selected' : '' }}>Semua Bulan</option>
        @foreach($months as $monthNumber => $monthName)
            <option value="{{ $monthNumber }}" {{ (string) $selectedMonth === (string) $monthNumber ? 'selected' : '' }}>{{ $monthName }}</option>
        @endforeach
    </select>
    <select name="tahun" class="compact-input">
        @foreach($availableYears as $availableYear)
            <option value="{{ $availableYear }}" {{ (int) $selectedYear === (int) $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
        @endforeach
    </select>
    <select name="jenis" class="compact-input">
        <option value="semua" {{ $selectedType === 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
        <option value="pemasukan" {{ $selectedType === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
        <option value="pengeluaran" {{ $selectedType === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
    </select>
    <button type="submit" class="btn-primary-custom">
        <span class="material-symbols-outlined" style="font-size:18px;">filter_alt</span>
        Terapkan Filter
    </button>
    <a href="{{ route('laporan.index') }}" class="btn-secondary-custom">Reset</a>
</form>

<div id="printArea">
    <div class="d-none d-print-block mb-4">
        <h2 style="margin:0;color:#00288e;font-weight:800;">Laporan Keuangan Kost AJ Lanraki</h2>
        <p style="margin:4px 0 0;color:#444653;">{{ $filterLabel }}</p>
        <p style="margin:2px 0 0;color:#444653;">Dicetak: {{ now()->locale('id')->translatedFormat('d M Y H:i') }}</p>
        <hr>
    </div>

    <div class="summary-strip mb-4">
        <div class="finance-panel">
            <div class="label"><span class="material-symbols-outlined">trending_up</span> Total Pemasukan</div>
            <div class="value text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="finance-panel">
            <div class="label"><span class="material-symbols-outlined">trending_down</span> Total Pengeluaran</div>
            <div class="value text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="finance-panel primary">
            <div class="label"><span class="material-symbols-outlined">account_balance</span> Saldo Bersih Periode</div>
            <div class="value">Rp {{ number_format($saldoBersih, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5><span class="material-symbols-outlined">monitoring</span> Tren Keuangan</h5>
                    <span class="badge-status badge-blue">{{ $periodeLabel }}</span>
                </div>
                <div class="content-card-body">
                    <div style="height: 320px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5><span class="material-symbols-outlined">donut_large</span> Distribusi Pengeluaran</h5>
                </div>
                <div class="content-card-body">
                    @if(count($chartKategoriLabels) > 0 && array_sum($chartKategoriTotals) > 0)
                        <div style="height: 320px;" class="screen-chart">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="table-scroll mt-3">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Transaksi</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pengeluaranKategori as $kategori)
                                    <tr>
                                        <td>{{ $kategori['kategori'] }}</td>
                                        <td class="text-end">{{ $kategori['jumlah_transaksi'] }}</td>
                                        <td class="text-end fw-bold text-danger">Rp {{ number_format($kategori['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <span class="material-symbols-outlined">donut_large</span>
                            <h6>Belum ada data kategori</h6>
                            <p>Data pengeluaran per kategori akan muncul sesuai filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @if($selectedType !== 'pengeluaran')
        <div class="{{ $selectedType === 'pemasukan' ? 'col-12' : 'col-xl-6' }}">
            <div class="content-card">
                <div class="content-card-header">
                    <h5><span class="material-symbols-outlined">payments</span> Daftar Pemasukan</h5>
                    <span class="badge-status badge-success">{{ $latestPemasukan->count() }} data</span>
                </div>
                <div class="content-card-body flush">
                    @if($latestPemasukan->count() > 0)
                    <div class="table-scroll">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Penghuni</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($latestPemasukan as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge-status {{ $item->kategori === 'pembayaran_kost' ? 'badge-blue' : 'badge-warning' }}">
                                            {{ $item->kategori === 'pembayaran_kost' ? 'Pembayaran Kost' : 'Pemasukan Lainnya' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($item->penghuni)->nama ?? 'Pemasukan lainnya' }}</td>
                                    <td>{{ $item->keterangan ?: 'Pembayaran kost' }}</td>
                                    <td><span class="badge-status badge-success">Lunas/Tercatat</span></td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="empty-state py-4">
                            <h6>Belum ada pemasukan sesuai filter</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($selectedType !== 'pemasukan')
        <div class="{{ $selectedType === 'pengeluaran' ? 'col-12' : 'col-xl-6' }}">
            <div class="content-card">
                <div class="content-card-header">
                    <h5><span class="material-symbols-outlined">account_balance_wallet</span> Daftar Pengeluaran</h5>
                    <span class="badge-status badge-danger">{{ $latestPengeluaran->count() }} data</span>
                </div>
                <div class="content-card-body flush">
                    @if($latestPengeluaran->count() > 0)
                    <div class="table-scroll">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($latestPengeluaran as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td><span class="badge-status badge-blue">{{ $item->kategori }}</span></td>
                                    <td>{{ $item->keterangan ?: 'Biaya operasional' }}</td>
                                    <td><span class="badge-status badge-warning">Tercatat</span></td>
                                    <td class="text-end fw-bold text-danger">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="empty-state py-4">
                            <h6>Belum ada pengeluaran sesuai filter</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Pemasukan',
                    data: {!! json_encode($chartPemasukan) !!},
                    backgroundColor: '#9fb2e9',
                    borderRadius: 4,
                    borderWidth: 0
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($chartPengeluaran) !!},
                    backgroundColor: '#f3b7bd',
                    borderRadius: 4,
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + Number(value).toLocaleString('id-ID'),
                        font: { family: 'Inter', size: 11 }
                    },
                    grid: { color: '#e5e7eb' }
                },
                x: {
                    ticks: { font: { family: 'Inter', size: 11 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'Inter', weight: '700', size: 12 }, boxWidth: 12 }
                },
                tooltip: {
                    callbacks: {
                        label: context => `${context.dataset.label}: Rp ${Number(context.parsed.y).toLocaleString('id-ID')}`
                    }
                }
            }
        }
    });

    @if(count($chartKategoriLabels) > 0 && array_sum($chartKategoriTotals) > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartKategoriLabels) !!},
            datasets: [{
                data: {!! json_encode($chartKategoriTotals) !!},
                backgroundColor: ['#1e40af', '#0058be', '#93c5fd', '#f59e0b', '#dc2626', '#6b7280', '#10b981', '#8b5cf6'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { family: 'Inter', size: 11 }, boxWidth: 12 }
                },
                tooltip: {
                    callbacks: {
                        label: context => `${context.label}: Rp ${Number(context.parsed).toLocaleString('id-ID')}`
                    }
                }
            }
        }
    });
    @endif

    function printCleanReport() {
        const source = document.getElementById('printArea').cloneNode(true);
        const canvases = document.querySelectorAll('#printArea canvas');
        const clonedCanvases = source.querySelectorAll('canvas');

        canvases.forEach((canvas, index) => {
            const image = document.createElement('img');
            image.src = canvas.toDataURL('image/png');
            image.style.maxWidth = '100%';
            image.style.height = 'auto';
            image.style.display = 'block';
            if (clonedCanvases[index]) {
                clonedCanvases[index].replaceWith(image);
            }
        });

        const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
            .map(node => node.outerHTML)
            .join('\n');

        const printWindow = window.open('', '_blank', 'width=1100,height=800');
        if (!printWindow) {
            alert('Popup cetak diblokir browser. Izinkan popup untuk mencetak laporan.');
            return;
        }
        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Laporan Keuangan Kost AJ Lanraki</title>
                ${styles}
                <style>
                    @page { size: A4; margin: 12mm; }
                    body { background: #fff !important; padding: 0; color: #111827 !important; }
                    .print-document { max-width: 100%; color: #111827 !important; }
                    .content-card, .finance-panel { box-shadow: none !important; break-inside: avoid; background:#fff !important; color:#111827 !important; border:1px solid #d1d5db !important; }
                    .content-card-header, .table-modern thead th { background:#f9fafb !important; color:#111827 !important; }
                    .finance-panel.primary { background:#fff !important; color:#111827 !important; }
                    .table-modern td, .table-modern th { color:#111827 !important; border-color:#d1d5db !important; }
                    table { page-break-inside: auto; }
                    tr { page-break-inside: avoid; page-break-after: auto; }
                    .d-print-block { display: block !important; }
                    .no-print { display: none !important; }
                </style>
            </head>
            <body>
                <main class="print-document">${source.innerHTML}</main>
                <script>
                    window.onload = function() {
                        setTimeout(function(){ window.print(); }, 250);
                        setTimeout(function(){ window.close(); }, 900);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush
