<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\ActivityLog;
use App\Services\FinanceService;
use App\Http\Requests\StorePengeluaranRequest;
use App\Http\Requests\UpdatePengeluaranRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->filled('search')) {
            $query->search(trim($request->search));
        }

        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }

        $query->dateRange($request->tanggal_mulai, $request->tanggal_selesai);

        // Paginate history list
        $pengeluarans = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(10)->withQueryString();

        $financeSummary = $this->financeService->getSummary();
        $totalPengeluaran = $financeSummary['totalPengeluaran'];

        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $pengeluaranBulanIni = (float) Pengeluaran::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('jumlah');

        $kategoriList = $this->kategoriList();

        return view('pengeluaran.index', compact('pengeluarans', 'totalPengeluaran', 'pengeluaranBulanIni', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = $this->kategoriList();
        return view('pengeluaran.create', compact('kategoriList'));
    }

    public function store(StorePengeluaranRequest $request)
    {
        $data = $request->validated();

        if ($data['kategori'] === '__custom__') {
            $data['kategori'] = trim($data['kategori_baru']);
        }
        unset($data['kategori_baru']);

        $pengeluaran = Pengeluaran::create($data);

        ActivityLog::log('Catat Pengeluaran', "Mencatat pengeluaran untuk {$pengeluaran->kategori} sebesar " . $pengeluaran->formatted_jumlah);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoriList = $this->kategoriList();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    public function update(UpdatePengeluaranRequest $request, Pengeluaran $pengeluaran)
    {
        $data = $request->validated();

        if ($data['kategori'] === '__custom__') {
            $data['kategori'] = trim($data['kategori_baru']);
        }
        unset($data['kategori_baru']);

        $pengeluaran->update($data);

        ActivityLog::log('Update Pengeluaran', "Memperbarui pengeluaran ID #{$pengeluaran->id}");

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $desc = "Menghapus pengeluaran ID #{$pengeluaran->id} (Soft Delete)";
        $pengeluaran->delete();

        ActivityLog::log('Hapus Pengeluaran', $desc);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }

    private function kategoriList(): array
    {
        $defaultCategories = ['Listrik', 'Air', 'Kebersihan', 'Perbaikan', 'Internet', 'Keamanan', 'ATK', 'Lainnya'];
        $storedCategories = Pengeluaran::query()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return collect($defaultCategories)
            ->merge($storedCategories)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
