<?php
namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller {
    public function index(){
        $year = Carbon::now()->year;
        $totalKamar = Kamar::count();
        $totalPenghuni = Penghuni::whereNull('tanggal_keluar')->count();
        $totalPemasukan = (float) Pemasukan::sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $okupansi = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
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

        $incomeByMonth = Pemasukan::whereYear('tanggal', $year)->get()
            ->groupBy(fn ($item) => Carbon::parse($item->tanggal)->month)
            ->map(fn ($rows) => (float) $rows->sum('jumlah'));

        $expenseByMonth = Pengeluaran::whereYear('tanggal', $year)->get()
            ->groupBy(fn ($item) => Carbon::parse($item->tanggal)->month)
            ->map(fn ($rows) => (float) $rows->sum('jumlah'));

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

        return view('dashboard.index', compact(
            'totalKamar', 'totalPenghuni', 'totalPemasukan', 'totalPengeluaran',
            'saldoBersih', 'kamarTersedia', 'kamarTerisi', 'okupansi',
            'chartLabels', 'chartPemasukan', 'chartPengeluaran', 'latestTransaksi', 'year', 'penghuniLunas', 'penghuniBelumLunas'
        ));
    }
}
