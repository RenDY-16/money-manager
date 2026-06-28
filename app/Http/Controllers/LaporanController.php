<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
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

        $allPemasukan = Pemasukan::with('penghuni.kamar')
            ->whereYear('tanggal', $selectedYear)
            ->get();

        $allPengeluaran = Pengeluaran::whereYear('tanggal', $selectedYear)
            ->get();

        $periodPemasukan = $this->filterByMonth($allPemasukan, $selectedMonth);
        $periodPengeluaran = $this->filterByMonth($allPengeluaran, $selectedMonth);

        // Ringkasan keuangan tetap memakai pemasukan dan pengeluaran periode yang sama.
        // Filter jenis hanya mengatur daftar transaksi yang ditampilkan, sehingga saldo tidak berubah negatif hanya karena admin memilih jenis pengeluaran.
        $totalPemasukan = (float) $periodPemasukan->sum('jumlah');
        $totalPengeluaran = (float) $periodPengeluaran->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $filteredPemasukan = $selectedType === 'pengeluaran' ? collect() : $periodPemasukan;
        $filteredPengeluaran = $selectedType === 'pemasukan' ? collect() : $periodPengeluaran;

        $chartMonths = $selectedMonth === 'semua' ? array_keys($months) : [$selectedMonth];
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        foreach ($chartMonths as $monthNumber) {
            $chartLabels[] = $months[$monthNumber];
            $chartPemasukan[] = (float) $allPemasukan
                ->filter(fn ($item) => Carbon::parse($item->tanggal)->month === (int) $monthNumber)
                ->sum('jumlah');
            $chartPengeluaran[] = (float) $allPengeluaran
                ->filter(fn ($item) => Carbon::parse($item->tanggal)->month === (int) $monthNumber)
                ->sum('jumlah');
        }

        $pengeluaranKategori = $periodPengeluaran
            ->groupBy('kategori')
            ->map(function ($rows, $kategori) {
                return [
                    'kategori' => $kategori ?: 'Lainnya',
                    'total' => (float) $rows->sum('jumlah'),
                    'jumlah_transaksi' => $rows->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $chartKategoriLabels = $pengeluaranKategori->pluck('kategori')->all();
        $chartKategoriTotals = $pengeluaranKategori->pluck('total')->all();

        $latestPemasukan = $filteredPemasukan
            ->sortByDesc('tanggal')
            ->values();

        $latestPengeluaran = $filteredPengeluaran
            ->sortByDesc('tanggal')
            ->values();

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

    private function filterByMonth(Collection $rows, int|string $selectedMonth): Collection
    {
        if ($selectedMonth === 'semua') {
            return $rows;
        }

        return $rows
            ->filter(fn ($item) => Carbon::parse($item->tanggal)->month === (int) $selectedMonth)
            ->values();
    }

    private function availableYears(): array
    {
        $currentYear = Carbon::now()->year;
        $yearRange = range($currentYear - 3, $currentYear + 10);

        $years = collect($yearRange)
            ->merge(Pemasukan::pluck('tanggal')->map(fn ($date) => Carbon::parse($date)->year))
            ->merge(Pengeluaran::pluck('tanggal')->map(fn ($date) => Carbon::parse($date)->year))
            ->filter()
            ->unique()
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
