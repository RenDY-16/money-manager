<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Penghuni;
use App\Models\ActivityLog;
use App\Services\TenantService;
use App\Services\FinanceService;
use App\Http\Requests\StorePemasukanRequest;
use App\Http\Requests\UpdatePemasukanRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemasukanController extends Controller
{
    protected TenantService $tenantService;
    protected FinanceService $financeService;

    public function __construct(TenantService $tenantService, FinanceService $financeService)
    {
        $this->tenantService = $tenantService;
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $query = Pemasukan::with('penghuni.kamar');

        if ($request->filled('search')) {
            $query->search(trim($request->search));
        }

        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }

        $query->dateRange($request->tanggal_mulai, $request->tanggal_selesai);

        // Paginate history list
        $pemasukans = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(10)->withQueryString();

        // Data for form dropdowns
        $penghunis = Penghuni::active()->with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();

        // Dynamic Finance Stats using service
        $financeSummary = $this->financeService->getSummary();
        $totalPemasukan = $financeSummary['totalPemasukan'];

        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $pemasukanBulanIni = (float) Pemasukan::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('jumlah');

        $periodeTagihan = Carbon::now()->locale('id')->translatedFormat('F Y');

        // Dynamic Unpaid Reminders using service
        $penghuniBelumBayar = $this->tenantService->getBelumBayarPenghunis($periodeTagihan);

        $jumlahPenghuniAktif = Penghuni::active()->count();
        $penghuniSudahBayarIds = $this->tenantService->getLunasPenghuniIds($awalBulan, $akhirBulan);
        $jumlahLunas = count($penghuniSudahBayarIds);
        $jumlahBelumLunas = $penghuniBelumBayar->count();

        return view('pemasukan.index', compact(
            'pemasukans', 'penghunis', 'kategoriPemasukan', 'totalPemasukan', 'pemasukanBulanIni',
            'penghuniBelumBayar', 'periodeTagihan', 'jumlahPenghuniAktif', 'jumlahLunas', 'jumlahBelumLunas'
        ));
    }

    public function create()
    {
        $penghunis = Penghuni::active()->with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();
        return view('pemasukan.create', compact('penghunis', 'kategoriPemasukan'));
    }

    public function store(StorePemasukanRequest $request)
    {
        $data = $request->validated();

        // Populate business defaults
        if ($data['kategori'] === 'pembayaran_kost') {
            $penghuni = Penghuni::with('kamar')->findOrFail($data['penghuni_id']);
            if (empty($data['jumlah'])) {
                $data['jumlah'] = (float) optional($penghuni->kamar)->harga;
            }
            if (empty($data['keterangan'])) {
                $periode = Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('F Y');
                $data['keterangan'] = 'Pembayaran kost bulan ' . $periode;
            }
        } else {
            $data['penghuni_id'] = null;
        }

        $pemasukan = Pemasukan::create($data);

        $label = $pemasukan->kategori === 'pembayaran_kost'
            ? "Mencatat pembayaran kost penghuni: " . optional($pemasukan->penghuni)->nama
            : "Mencatat pemasukan lainnya";
        ActivityLog::log('Catat Pemasukan', $label . " sebesar " . $pemasukan->formatted_jumlah);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit(Pemasukan $pemasukan)
    {
        $penghunis = Penghuni::active()->with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();
        return view('pemasukan.edit', compact('pemasukan', 'penghunis', 'kategoriPemasukan'));
    }

    public function update(UpdatePemasukanRequest $request, Pemasukan $pemasukan)
    {
        $data = $request->validated();

        if ($data['kategori'] === 'pembayaran_kost') {
            $penghuni = Penghuni::with('kamar')->findOrFail($data['penghuni_id']);
            if (empty($data['jumlah'])) {
                $data['jumlah'] = (float) optional($penghuni->kamar)->harga;
            }
            if (empty($data['keterangan'])) {
                $periode = Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('F Y');
                $data['keterangan'] = 'Pembayaran kost bulan ' . $periode;
            }
        } else {
            $data['penghuni_id'] = null;
        }

        $pemasukan->update($data);

        ActivityLog::log('Update Pemasukan', "Memperbarui transaksi pemasukan ID #{$pemasukan->id}");

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Pemasukan $pemasukan)
    {
        $desc = "Menghapus transaksi pemasukan ID #{$pemasukan->id} (Soft Delete)";
        $pemasukan->delete();

        ActivityLog::log('Hapus Pemasukan', $desc);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil dihapus!');
    }

    public function showKwitansi(Pemasukan $pemasukan)
    {
        $pemasukan->load('penghuni.kamar');
        return view('pemasukan.kwitansi', compact('pemasukan'));
    }

    private function kategoriPemasukan(): array
    {
        return [
            'pembayaran_kost' => 'Pembayaran Kost',
            'pemasukan_lainnya' => 'Pemasukan Lainnya',
        ];
    }
}
