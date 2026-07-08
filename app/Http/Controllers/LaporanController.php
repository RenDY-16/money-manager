<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Services\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        return view('laporan.index', $this->buildReportData($request));
    }

    public function exportExcel(Request $request)
    {
        $data = $this->buildReportData($request);
        $filename = 'laporan-keuangan-kost-aj-' . now()->format('Ymd-His') . '.xls';

        return response()
            ->view('laporan.export_excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    private function buildReportData(Request $request): array
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $availableYears = $this->availableYears();
        $selectedYear = (int) $request->input('tahun', Carbon::now()->year);
        $selectedMonth = $request->input('bulan', 'semua');
        $selectedType = $request->input('jenis', 'semua');

        if (! in_array($selectedYear, $availableYears, true)) {
            $availableYears[] = $selectedYear;
            rsort($availableYears);
        }

        if ($selectedMonth !== 'semua') {
            $selectedMonth = (int) $selectedMonth;
            if ($selectedMonth < 1 || $selectedMonth > 12) {
                $selectedMonth = 'semua';
            }
        }

        if (! in_array($selectedType, ['semua', 'pemasukan', 'pengeluaran'], true)) {
            $selectedType = 'semua';
        }

        // Optimized Queries: Filter at SQL level instead of fetching entire year
        $pemasukanQuery = Pemasukan::with('penghuni.kamar')->whereYear('tanggal', $selectedYear);
        if ($selectedMonth !== 'semua') {
            $pemasukanQuery->whereMonth('tanggal', $selectedMonth);
        }
        $periodPemasukan = $pemasukanQuery->get();

        $pengeluaranQuery = Pengeluaran::whereYear('tanggal', $selectedYear);
        if ($selectedMonth !== 'semua') {
            $pengeluaranQuery->whereMonth('tanggal', $selectedMonth);
        }
        $periodPengeluaran = $pengeluaranQuery->get();

        // Financial totals
        $totalPemasukan = (float) $periodPemasukan->sum('jumlah');
        $totalPengeluaran = (float) $periodPengeluaran->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $filteredPemasukan = $selectedType === 'pengeluaran' ? collect() : $periodPemasukan;
        $filteredPengeluaran = $selectedType === 'pemasukan' ? collect() : $periodPengeluaran;

        // Use service to fetch monthly breakdown efficiently using SQL Group By
        $breakdown = $this->financeService->getMonthlyBreakdown($selectedYear);
        $incomeByMonth = $breakdown['income'];
        $expenseByMonth = $breakdown['expense'];

        $chartMonths = $selectedMonth === 'semua' ? array_keys($months) : [$selectedMonth];
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        foreach ($chartMonths as $monthNumber) {
            $chartLabels[] = $months[$monthNumber];
            $chartPemasukan[] = $incomeByMonth[$monthNumber] ?? 0.0;
            $chartPengeluaran[] = $expenseByMonth[$monthNumber] ?? 0.0;
        }

        // SQL aggregate category query instead of grouping in memory
        $pengeluaranKategoriQuery = Pengeluaran::whereYear('tanggal', $selectedYear);
        if ($selectedMonth !== 'semua') {
            $pengeluaranKategoriQuery->whereMonth('tanggal', $selectedMonth);
        }
        $pengeluaranKategori = $pengeluaranKategoriQuery
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as jumlah_transaksi')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'kategori' => $row->kategori ?: 'Lainnya',
                    'total' => (float) $row->total,
                    'jumlah_transaksi' => (int) $row->jumlah_transaksi,
                ];
            });

        $chartKategoriLabels = $pengeluaranKategori->pluck('kategori')->all();
        $chartKategoriTotals = $pengeluaranKategori->pluck('total')->all();

        $latestPemasukan = $filteredPemasukan->sortByDesc('tanggal')->values();
        $latestPengeluaran = $filteredPengeluaran->sortByDesc('tanggal')->values();

        $filterLabel = $this->filterLabel($months, $selectedMonth, $selectedYear, $selectedType);
        $periodeLabel = $this->periodeLabel($months, $selectedMonth, $selectedYear);

        return compact(
            'chartLabels', 'chartPemasukan', 'chartPengeluaran',
            'totalPemasukan', 'totalPengeluaran', 'saldoBersih',
            'chartKategoriLabels', 'chartKategoriTotals', 'pengeluaranKategori',
            'latestPemasukan', 'latestPengeluaran',
            'months', 'availableYears', 'selectedYear', 'selectedMonth', 'selectedType', 'filterLabel', 'periodeLabel'
        );
    }

    private function availableYears(): array
    {
        $currentYear = Carbon::now()->year;
        $yearRange = range($currentYear - 3, $currentYear + 10);

        // Fetch min & max dates using high speed index searches instead of pulling all dates
        $minPemasukan = Pemasukan::min('tanggal');
        $minPengeluaran = Pengeluaran::min('tanggal');
        $maxPemasukan = Pemasukan::max('tanggal');
        $maxPengeluaran = Pengeluaran::max('tanggal');

        $minYear = collect([$minPemasukan, $minPengeluaran])->filter()->map(fn($d) => Carbon::parse($d)->year)->min() ?? ($currentYear - 3);
        $maxYear = collect([$maxPemasukan, $maxPengeluaran])->filter()->map(fn($d) => Carbon::parse($d)->year)->max() ?? ($currentYear + 10);

        $years = collect(range($minYear, $maxYear))
            ->merge($yearRange)
            ->unique()
            ->sort()
            ->values()
            ->all();

        rsort($years);

        return $years;
    }

    private function filterLabel(array $months, int|string $selectedMonth, int $selectedYear, string $selectedType): string
    {
        $monthText = $selectedMonth === 'semua' ? 'Semua bulan' : $months[$selectedMonth];
        $typeText = match ($selectedType) {
            'pemasukan' => 'Pemasukan',
            'pengeluaran' => 'Pengeluaran',
            default => 'Semua transaksi',
        };

        return $typeText . ' | ' . $monthText . ' ' . $selectedYear;
    }

    private function periodeLabel(array $months, int|string $selectedMonth, int $selectedYear): string
    {
        return ($selectedMonth === 'semua' ? 'Semua bulan' : $months[$selectedMonth]) . ' ' . $selectedYear;
    }
}
