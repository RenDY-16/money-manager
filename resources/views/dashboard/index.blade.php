@extends('layouts.app')

@section('title', 'Financial Management')
@section('subtitle', 'Ringkasan operasional dan keuangan kost')

@section('content')
@php
    $maxChart = max(array_merge($chartPemasukan, $chartPengeluaran, [1]));
@endphp

<div class="metric-grid metric-grid-5">
    <div class="metric-card card-hover">
        <div class="metric-top">
            <span class="material-symbols-outlined metric-icon {{ $trendPemasukan >= 0 ? 'success' : 'danger' }}">{{ $trendPemasukan >= 0 ? 'trending_up' : 'trending_down' }}</span>
            <span class="metric-trend" style="color:{{ $trendPemasukan >= 0 ? 'var(--success)' : 'var(--danger)' }}">{{ ($trendPemasukan >= 0 ? '+' : '') . number_format($trendPemasukan, 1) }}%</span>
        </div>
        <div class="metric-label">Total Pemasukan</div>
        <div class="metric-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        <div class="metric-note">Akumulasi pembayaran masuk</div>
    </div>

    <div class="metric-card card-hover">
        <div class="metric-top">
            <span class="material-symbols-outlined metric-icon {{ $trendPengeluaran <= 0 ? 'success' : 'danger' }}">{{ $trendPengeluaran <= 0 ? 'trending_down' : 'trending_up' }}</span>
            <span class="metric-trend" style="color:{{ $trendPengeluaran <= 0 ? 'var(--success)' : 'var(--danger)' }}">{{ ($trendPengeluaran >= 0 ? '+' : '') . number_format($trendPengeluaran, 1) }}%</span>
        </div>
        <div class="metric-label">Total Pengeluaran</div>
        <div class="metric-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        <div class="metric-note">Biaya operasional tercatat</div>
    </div>

    <div class="metric-card card-hover">
        <div class="metric-top">
            <span class="material-symbols-outlined metric-icon">account_balance_wallet</span>
            <span class="metric-trend">Net</span>
        </div>
        <div class="metric-label">Saldo Bersih</div>
        <div class="metric-value">Rp {{ number_format($saldoBersih, 0, ',', '.') }}</div>
        <div class="metric-note">Selisih pemasukan dan biaya</div>
    </div>

    <div class="metric-card card-hover">
        <div class="metric-top">
            <span class="material-symbols-outlined metric-icon warning">bed</span>
            <span class="metric-trend">{{ $okupansi }}%</span>
        </div>
        <div class="metric-label">Okupansi Kamar</div>
        <div class="metric-value">{{ $kamarTerisi }} / {{ $totalKamar }}</div>
        <div class="metric-note">Kamar terisi saat ini</div>
    </div>

    <div class="metric-card card-hover">
        <div class="metric-top">
            <span class="material-symbols-outlined metric-icon">calendar_month</span>
            <span class="metric-trend">{{ date('d M') }}</span>
        </div>
        <div class="metric-label">Kamar Tersedia</div>
        <div class="metric-value">{{ $kamarTersedia }}</div>
        <div class="metric-note">Siap ditempati penghuni baru</div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">bar_chart</span> Statistik Keuangan</h5>
                <span class="badge-status badge-blue">{{ $year }}</span>
            </div>
            <div class="content-card-body">
                <div style="height: 310px; position: relative;">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">fact_check</span> Status Pembayaran</h5>
            </div>
            <div class="content-card-body">
                <div class="finance-panel mb-3">
                    <div class="label"><span class="material-symbols-outlined">door_open</span> Kamar Tersedia</div>
                    <div class="value text-success">{{ $kamarTersedia }} Kamar</div>
                </div>
                <div class="finance-panel mb-3">
                    <div class="label"><span class="material-symbols-outlined">groups</span> Penghuni Aktif</div>
                    <div class="value">{{ $totalPenghuni }} Orang</div>
                </div>
                <div class="finance-panel mb-3">
                    <div class="label"><span class="material-symbols-outlined">check_circle</span> Lunas Bulan Ini</div>
                    <div class="value text-success">{{ $penghuniLunas }} Orang</div>
                </div>
                <div class="finance-panel mb-3">
                    <div class="label"><span class="material-symbols-outlined">pending_actions</span> Belum Lunas</div>
                    <div class="value text-danger">{{ $penghuniBelumLunas }} Orang</div>
                </div>
                <a href="{{ route('pemasukan.index') }}" class="btn-primary-custom w-100 mb-2">
                    <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                    Input Pembayaran
                </a>
                <a href="{{ route('laporan.index') }}" class="btn-secondary-custom w-100">
                    <span class="material-symbols-outlined" style="font-size:18px;">description</span>
                    Buka Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5><span class="material-symbols-outlined">receipt_long</span> Transaksi Terbaru</h5>
        <a href="{{ route('laporan.index') }}" class="btn-secondary-custom">Lihat Detail</a>
    </div>
    <div class="content-card-body flush">
        @if($latestTransaksi->count() > 0)
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nama/Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestTransaksi as $transaksi)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transaksi['tanggal'])->locale('id')->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge-status {{ $transaksi['jenis'] === 'Pemasukan' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $transaksi['jenis'] }}
                                    </span>
                                </td>
                                <td class="fw-bold text-primary">{{ $transaksi['nama'] }}</td>
                                <td>{{ $transaksi['keterangan'] }}</td>
                                <td class="text-end fw-bold {{ $transaksi['jenis'] === 'Pemasukan' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaksi['jenis'] === 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($transaksi['jumlah'], 0, ',', '.') }}
                                </td>
                                <td><span class="badge-status badge-paid">{{ $transaksi['status'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <span class="material-symbols-outlined">receipt_long</span>
                <h6>Belum ada transaksi</h6>
                <p>Input pemasukan atau pengeluaran untuk melihat aktivitas terbaru.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
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
    });
</script>
@endpush
