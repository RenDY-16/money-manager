<?php
namespace App\Http\Controllers;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;

class DashboardController extends Controller {
    public function index(){
        $totalKamar = Kamar::count();
        $totalPenghuni = Penghuni::whereNull('tanggal_keluar')->count();
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();

        return view('dashboard.index', compact(
            'totalKamar', 'totalPenghuni', 'totalPemasukan',
            'totalPengeluaran', 'kamarTersedia', 'kamarTerisi'
        ));
    }
}
