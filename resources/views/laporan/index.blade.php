@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('subtitle', 'Laporan grafis pemasukan dan pengeluaran kost tahun ' . $year)

@section('content')
<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card gold animate-in">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706;">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan ({{ $year }})</div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="stat-card rose animate-in">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #e11d48;">
                <i class="bi bi-graph-down-arrow"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran ({{ $year }})</div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12">
        <div class="stat-card blue animate-in">
            <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: var(--navy-600);">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-value" style="color: {{ $saldoBersih >= 0 ? '#059669' : '#e11d48' }};">
                Rp {{ number_format($saldoBersih, 0, ',', '.') }}
            </div>
            <div class="stat-label">Saldo Bersih ({{ $year }})</div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row g-4 mb-4">
    <!-- Bar Chart (Monthly) -->
    <div class="col-lg-8">
        <div class="content-card animate-in h-100">
            <div class="content-card-header">
                <h5><i class="bi bi-bar-chart-line-fill"></i> Grafik Keuangan Bulanan ({{ $year }})</h5>
            </div>
            <div class="content-card-body" style="padding: 24px;">
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Doughnut Chart (Categories) -->
    <div class="col-lg-4">
        <div class="content-card animate-in h-100">
            <div class="content-card-header">
                <h5><i class="bi bi-pie-chart-fill"></i> Kategori Pengeluaran Terbesar</h5>
            </div>
            <div class="content-card-body" style="padding: 24px; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 350px;">
                @if(count($chartKategoriLabels) > 0 && array_sum($chartKategoriTotals) > 0)
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @else
                    <div class="empty-state py-4">
                        <i class="bi bi-info-circle" style="font-size: 40px; color: #cbd5e1;"></i>
                        <h6 class="mt-2 text-muted">Belum Ada Data Pengeluaran</h6>
                        <p class="text-muted small">Data kategori akan muncul setelah ada transaksi pengeluaran di tahun ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detailed Table Section -->
<div class="content-card animate-in">
    <div class="content-card-header">
        <h5><i class="bi bi-table"></i> Rincian Bulanan</h5>
    </div>
    <div class="content-card-body">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pemasukan</th>
                        <th>Total Pengeluaran</th>
                        <th>Selisih (Laba/Rugi)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chartLabels as $index => $label)
                    @php
                        $pemasukan = $chartPemasukan[$index];
                        $pengeluaran = $chartPengeluaran[$index];
                        $selisih = $pemasukan - $pengeluaran;
                    @endphp
                    <tr>
                        <td><strong>{{ $label }}</strong></td>
                        <td class="text-success">+ Rp {{ number_format($pemasukan, 0, ',', '.') }}</td>
                        <td class="text-danger">- Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                        <td>
                            @if($selisih > 0)
                                <strong class="text-success">+ Rp {{ number_format($selisih, 0, ',', '.') }}</strong>
                            @elseif($selisih < 0)
                                <strong class="text-danger">Rp {{ number_format($selisih, 0, ',', '.') }}</strong>
                            @else
                                <span class="text-muted">Rp 0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Monthly Financial Chart (Bar)
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($chartPemasukan) !!},
                        backgroundColor: '#10b981', // emerald color
                        borderRadius: 6,
                        borderWidth: 0,
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($chartPengeluaran) !!},
                        backgroundColor: '#f43f5e', // rose color
                        borderRadius: 6,
                        borderWidth: 0,
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
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            },
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Inter',
                                weight: '600',
                                size: 12
                            },
                            boxWidth: 15,
                            boxHeight: 15,
                            useBorderRadius: true,
                            borderRadius: 4
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // 2. Expense Category Chart (Doughnut)
        @if(count($chartKategoriLabels) > 0 && array_sum($chartKategoriTotals) > 0)
        const ctxKategori = document.getElementById('categoryChart').getContext('2d');
        
        // Define color map to keep colors consistent for categories
        const categoryColors = {
            'Listrik': '#f59e0b',    // Gold
            'Air': '#3b82f6',        // Blue
            'Kebersihan': '#10b981',  // Emerald
            'Perbaikan': '#8b5cf6',   // Violet
            'Lainnya': '#64748b'     // Slate/Gray
        };
        
        const labels = {!! json_encode($chartKategoriLabels) !!};
        const backgroundColors = labels.map(label => categoryColors[label] || '#94a3b8');

        const categoryChart = new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: {!! json_encode($chartKategoriTotals) !!},
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            boxWidth: 12,
                            useBorderRadius: true,
                            borderRadius: 3
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    label += 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        @endif
    });
</script>
@endsection
