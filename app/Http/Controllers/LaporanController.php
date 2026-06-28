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

        $filteredPemasukan = $this->filterByMonth($allPemasukan, $selectedMonth);
        $filteredPengeluaran = $this->filterByMonth($allPengeluaran, $selectedMonth);

        if ($selectedType === 'pengeluaran') {
            $filteredPemasukan = collect();
        }

        if ($selectedType === 'pemasukan') {
            $filteredPengeluaran = collect();
        }

        $chartMonths = $selectedMonth === 'semua' ? array_keys($months) : [$selectedMonth];
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        foreach ($chartMonths as $monthNumber) {
            $chartLabels[] = $months[$monthNumber];
            $chartPemasukan[] = $selectedType === 'pengeluaran'
                ? 0
                : (float) $allPemasukan
                    ->filter(fn ($item) => Carbon::parse($item->tanggal)->month === (int) $monthNumber)
                    ->sum('jumlah');
            $chartPengeluaran[] = $selectedType === 'pemasukan'
                ? 0
                : (float) $allPengeluaran
                    ->filter(fn ($item) => Carbon::parse($item->tanggal)->month === (int) $monthNumber)
                    ->sum('jumlah');
        }

        $totalPemasukan = (float) $filteredPemasukan->sum('jumlah');
        $totalPengeluaran = (float) $filteredPengeluaran->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $pengeluaranKategori = $filteredPengeluaran
            ->groupBy('kategori')
            ->map(function ($rows, $kategori) {
                return [
                    'kategori' => $kategori ?: 'Lainnya',
                    'total' => (float) $rows->sum('jumlah'),
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

        return view('laporan.index', compact(
            'chartLabels', 'chartPemasukan', 'chartPengeluaran',
            'totalPemasukan', 'totalPengeluaran', 'saldoBersih',
            'chartKategoriLabels', 'chartKategoriTotals', 'latestPemasukan', 'latestPengeluaran',
            'months', 'availableYears', 'selectedYear', 'selectedMonth', 'selectedType', 'filterLabel'
        ));
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
        $yearRange = range($currentYear - 1, $currentYear + 10);

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
}
