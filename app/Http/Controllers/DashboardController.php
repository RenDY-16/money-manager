<?php
namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller {
    public function index(Request $request){
        // Filter support (Fitur 7)
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $selectedYear = (int) $request->input('tahun', Carbon::now()->year);
        $selectedMonth = $request->input('bulan', 'semua');

        if ($selectedMonth !== 'semua') {
            $selectedMonth = (int) $selectedMonth;
            if ($selectedMonth < 1 || $selectedMonth > 12) {
                $selectedMonth = 'semua';
            }
        }

        $year = $selectedYear;
        $totalKamar = Kamar::count();
        $totalPenghuni = Penghuni::whereNull('tanggal_keluar')->count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $okupansi = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;

        // Build date range based on filter
        if ($selectedMonth !== 'semua') {
            $filterStart = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth()->toDateString();
            $filterEnd = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()->toDateString();
        } else {
            $filterStart = Carbon::create($selectedYear, 1, 1)->startOfYear()->toDateString();
            $filterEnd = Carbon::create($selectedYear, 12, 31)->endOfYear()->toDateString();
        }

        $totalPemasukan = (float) Pemasukan::whereBetween('tanggal', [$filterStart, $filterEnd])->sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::whereBetween('tanggal', [$filterStart, $filterEnd])->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        // Trend calculation — current month vs previous month
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $awalBulanLalu = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $akhirBulanLalu = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $pemasukanBulanIni = (float) Pemasukan::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('jumlah');
        $pemasukanBulanLalu = (float) Pemasukan::whereBetween('tanggal', [$awalBulanLalu, $akhirBulanLalu])->sum('jumlah');
        $pengeluaranBulanIni = (float) Pengeluaran::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('jumlah');
        $pengeluaranBulanLalu = (float) Pengeluaran::whereBetween('tanggal', [$awalBulanLalu, $akhirBulanLalu])->sum('jumlah');

        $trendPemasukan = 0.0;
        if ($pemasukanBulanLalu > 0) {
            $trendPemasukan = (($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100;
        } elseif ($pemasukanBulanIni > 0) {
            $trendPemasukan = 100.0;
        }

        $trendPengeluaran = 0.0;
        if ($pengeluaranBulanLalu > 0) {
            $trendPengeluaran = (($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100;
        } elseif ($pengeluaranBulanIni > 0) {
            $trendPengeluaran = 100.0;
        }

        $penghuniLunasIds = Pemasukan::where('kategori', 'pembayaran_kost')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->whereNotNull('penghuni_id')
            ->pluck('penghuni_id')
            ->unique();
        $penghuniLunas = $penghuniLunasIds->count();
        $penghuniBelumLunas = max(0, $totalPenghuni - $penghuniLunas);

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // SQL aggregate month queries
        $isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "strftime('%m', tanggal)" : "MONTH(tanggal)";

        $incomeByMonth = Pemasukan::whereYear('tanggal', $year)
            ->selectRaw("$monthExpr as month, SUM(jumlah) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->mapWithKeys(fn($val, $key) => [(int)$key => (float)$val]);

        $expenseByMonth = Pengeluaran::whereYear('tanggal', $year)
            ->selectRaw("$monthExpr as month, SUM(jumlah) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->mapWithKeys(fn($val, $key) => [(int)$key => (float)$val]);

        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        foreach (range(1, 12) as $month) {
            $chartLabels[] = $monthNames[$month];
            $chartPemasukan[] = $incomeByMonth[$month] ?? 0;
            $chartPengeluaran[] = $expenseByMonth[$month] ?? 0;
        }

        $latestPemasukan = Pemasukan::with('penghuni')->latest()->take(5)->get()->map(function ($item) {
            $nama = $item->kategori === 'pemasukan_lainnya'
                ? 'Pemasukan lainnya'
                : (optional($item->penghuni)->nama ?? 'Penghuni terhapus');

            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'Pemasukan',
                'nama' => $nama,
                'keterangan' => $item->keterangan ?: ($item->kategori === 'pemasukan_lainnya' ? 'Pemasukan lainnya' : 'Pembayaran kost'),
                'jumlah' => (float) $item->jumlah,
                'status' => 'Berhasil',
            ];
        });

        $latestPengeluaran = Pengeluaran::latest()->take(5)->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'Pengeluaran',
                'nama' => $item->kategori,
                'keterangan' => $item->keterangan ?: 'Biaya operasional',
                'jumlah' => (float) $item->jumlah,
                'status' => 'Tercatat',
            ];
        });

        $latestTransaksi = (new Collection())
            ->merge($latestPemasukan)
            ->merge($latestPengeluaran)
            ->sortByDesc('tanggal')
            ->take(6)
            ->values();

        // Available years for filter
        $currentYear = Carbon::now()->year;
        $availableYears = range($currentYear - 3, $currentYear + 1);

        // Periode label for filter
        $periodeLabel = ($selectedMonth === 'semua' ? 'Semua bulan' : $months[$selectedMonth]) . ' ' . $selectedYear;

        return view('dashboard.index', compact(
            'totalKamar', 'totalPenghuni', 'totalPemasukan', 'totalPengeluaran',
            'saldoBersih', 'kamarTersedia', 'kamarTerisi', 'okupansi',
            'chartLabels', 'chartPemasukan', 'chartPengeluaran', 'latestTransaksi', 'year',
            'penghuniLunas', 'penghuniBelumLunas', 'trendPemasukan', 'trendPengeluaran',
            'months', 'availableYears', 'selectedYear', 'selectedMonth', 'periodeLabel'
        ));
    }
}
