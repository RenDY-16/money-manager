<?php
namespace App\Http\Controllers;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller {
    public function index() {
        $year = Carbon::now()->year;

        // Group by month
        $pemasukanBulanan = Pemasukan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
        ->whereYear('tanggal', $year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan')
        ->all();

        $pengeluaranBulanan = Pengeluaran::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
        ->whereYear('tanggal', $year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan')
        ->all();

        // Prepare 12 months array
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        foreach ($months as $num => $name) {
            $chartLabels[] = $name;
            $chartPemasukan[] = (float)($pemasukanBulanan[$num] ?? 0);
            $chartPengeluaran[] = (float)($pengeluaranBulanan[$num] ?? 0);
        }

        $totalPemasukan = array_sum($chartPemasukan);
        $totalPengeluaran = array_sum($chartPengeluaran);
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        // Group by category for the pie/doughnut chart
        $pengeluaranKategori = Pengeluaran::select(
            'kategori',
            DB::raw('SUM(jumlah) as total')
        )
        ->whereYear('tanggal', $year)
        ->groupBy('kategori')
        ->orderByDesc('total')
        ->get();

        $chartKategoriLabels = [];
        $chartKategoriTotals = [];

        foreach ($pengeluaranKategori as $item) {
            $chartKategoriLabels[] = $item->kategori;
            $chartKategoriTotals[] = (float)$item->total;
        }

        return view('laporan.index', compact(
            'chartLabels', 'chartPemasukan', 'chartPengeluaran',
            'totalPemasukan', 'totalPengeluaran', 'saldoBersih', 'year',
            'chartKategoriLabels', 'chartKategoriTotals'
        ));
    }
}
