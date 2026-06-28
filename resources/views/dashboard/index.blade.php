@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data kost Anda hari ini')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue animate-in">
            <div class="stat-icon">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div class="stat-value">{{ $totalKamar }}</div>
            <div class="stat-label">Total Kamar</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card emerald animate-in">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ $totalPenghuni }}</div>
            <div class="stat-label">Total Penghuni Aktif</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card gold animate-in">
            <div class="stat-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card rose animate-in">
            <div class="stat-icon">
                <i class="bi bi-graph-down-arrow"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="content-card animate-in">
            <div class="content-card-header">
                <h5><i class="bi bi-bar-chart-fill"></i> Ringkasan Keuangan</h5>
            </div>
            <div class="content-card-body" style="padding: 24px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); padding: 20px; border-radius: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="bi bi-arrow-up-circle-fill" style="color: #059669; font-size: 20px;"></i>
                                <span style="font-size: 13px; color: #065f46; font-weight: 600;">Pemasukan</span>
                            </div>
                            <div style="font-size: 22px; font-weight: 800; color: #065f46;">
                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background: linear-gradient(135deg, #fff1f2, #fce7f3); padding: 20px; border-radius: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="bi bi-arrow-down-circle-fill" style="color: #e11d48; font-size: 20px;"></i>
                                <span style="font-size: 13px; color: #9d174d; font-weight: 600;">Pengeluaran</span>
                            </div>
                            <div style="font-size: 22px; font-weight: 800; color: #9d174d;">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @php $saldo = $totalPemasukan - $totalPengeluaran; @endphp
                        <div style="background: linear-gradient(135deg, #eff6ff, #dbeafe); padding: 20px; border-radius: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="bi bi-wallet2" style="color: #1e40af; font-size: 20px;"></i>
                                <span style="font-size: 13px; color: #1e40af; font-weight: 600;">Saldo Bersih</span>
                            </div>
                            <div style="font-size: 26px; font-weight: 800; color: {{ $saldo >= 0 ? '#065f46' : '#e11d48' }};">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card animate-in">
            <div class="content-card-header">
                <h5><i class="bi bi-door-open-fill"></i> Status Kamar</h5>
            </div>
            <div class="content-card-body" style="padding: 24px;">
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Tersedia</span>
                        <span style="font-size: 13px; font-weight: 700; color: #059669;">{{ $kamarTersedia }}</span>
                    </div>
                    <div style="height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $totalKamar > 0 ? ($kamarTersedia / $totalKamar) * 100 : 0 }}%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 4px; transition: width 0.6s ease;"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Terisi</span>
                        <span style="font-size: 13px; font-weight: 700; color: #e11d48;">{{ $kamarTerisi }}</span>
                    </div>
                    <div style="height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $totalKamar > 0 ? ($kamarTerisi / $totalKamar) * 100 : 0 }}%; background: linear-gradient(90deg, #f43f5e, #fb7185); border-radius: 4px; transition: width 0.6s ease;"></div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                    <div style="font-size: 36px; font-weight: 800; color: var(--navy-900);">{{ $totalKamar }}</div>
                    <div style="font-size: 13px; color: #94a3b8; font-weight: 500;">Total Kamar</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
