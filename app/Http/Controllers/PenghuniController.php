<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\Pemasukan;
use App\Models\ActivityLog;
use App\Services\TenantService;
use App\Http\Requests\StorePenghuniRequest;
use App\Http\Requests\UpdatePenghuniRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenghuniController extends Controller
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        
        // Use service to get lunas IDs
        $penghuniLunasIds = $this->tenantService->getLunasPenghuniIds($awalBulan, $akhirBulan);

        $query = Penghuni::with('kamar');

        if ($request->filled('search')) {
            $query->search(trim($request->search));
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->active();
            } elseif ($request->status === 'keluar') {
                $query->whereNotNull('tanggal_keluar');
            }
        }

        if ($request->filled('pembayaran')) {
            if ($request->pembayaran === 'lunas') {
                $query->whereIn('id', $penghuniLunasIds);
            } elseif ($request->pembayaran === 'belum_lunas') {
                $query->active()->whereNotIn('id', $penghuniLunasIds);
            }
        }

        // Paginate for scalability
        $penghunis = $query->latest()->paginate(10)->withQueryString();

        // Map status on the paginated result
        $penghunis->getCollection()->transform(function ($penghuni) use ($penghuniLunasIds) {
            $penghuni->status_pembayaran_bulan_ini = in_array($penghuni->id, $penghuniLunasIds, true)
                ? 'lunas'
                : 'belum_lunas';
            return $penghuni;
        });

        // Optimized SQL Counts
        $totalPenghuniCount = Penghuni::count();
        $aktifPenghuniCount = Penghuni::active()->count();
        $nonaktifPenghuniCount = Penghuni::whereNotNull('tanggal_keluar')->count();
        
        // Lunas count is active and lunas
        $lunasCount = Penghuni::active()->whereIn('id', $penghuniLunasIds)->count();
        $belumLunasCount = max(0, $aktifPenghuniCount - $lunasCount);

        $periodeTagihan = Carbon::now()->locale('id')->translatedFormat('F Y');

        return view('penghuni.index', compact(
            'penghunis',
            'periodeTagihan',
            'totalPenghuniCount',
            'aktifPenghuniCount',
            'nonaktifPenghuniCount',
            'lunasCount',
            'belumLunasCount'
        ));
    }

    public function create()
    {
        $kamars = Kamar::available()->get();
        return view('penghuni.create', compact('kamars'));
    }

    public function show(Penghuni $penghuni)
    {
        $penghuni->load('kamar');

        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();

        $penghuniLunasIds = $this->tenantService->getLunasPenghuniIds($awalBulan, $akhirBulan);
        $statusBayar = in_array($penghuni->id, $penghuniLunasIds, true) ? 'lunas' : 'belum_lunas';

        $riwayatPembayaran = Pemasukan::where('penghuni_id', $penghuni->id)
            ->orderByDesc('tanggal')
            ->get();

        $totalDibayar = $riwayatPembayaran->sum('jumlah');

        return view('penghuni.show', compact('penghuni', 'statusBayar', 'riwayatPembayaran', 'totalDibayar'));
    }

    public function store(StorePenghuniRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $penghuni = Penghuni::create($data);
            Kamar::findOrFail($data['kamar_id'])->update(['status' => 'terisi']);
            ActivityLog::log('Tambah Penghuni', "Menambahkan penghuni baru: {$penghuni->nama} ke kamar " . optional($penghuni->kamar)->nomor_kamar);
        });

        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil ditambahkan!');
    }

    public function edit(Penghuni $penghuni)
    {
        $kamars = Kamar::all();
        return view('penghuni.edit', compact('penghuni', 'kamars'));
    }

    public function update(UpdatePenghuniRequest $request, Penghuni $penghuni)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $penghuni) {
            $oldKamarId = $penghuni->kamar_id;
            $newKamarId = $data['kamar_id'];

            if ($oldKamarId != $newKamarId) {
                Kamar::find($oldKamarId)?->update(['status' => 'tersedia']);
                Kamar::find($newKamarId)?->update(['status' => 'terisi']);
            }

            if (!empty($data['tanggal_keluar'])) {
                Kamar::find($newKamarId)?->update(['status' => 'tersedia']);
            } else {
                Kamar::find($newKamarId)?->update(['status' => 'terisi']);
            }

            $penghuni->update($data);
            ActivityLog::log('Update Penghuni', "Memperbarui data penghuni: {$penghuni->nama}");
        });

        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil diperbarui!');
    }

    public function destroy(Penghuni $penghuni)
    {
        DB::transaction(function () use ($penghuni) {
            Kamar::find($penghuni->kamar_id)?->update(['status' => 'tersedia']);
            $name = $penghuni->nama;
            $penghuni->delete();
            ActivityLog::log('Hapus Penghuni', "Menghapus penghuni: {$name} (Soft Delete)");
        });

        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil dihapus!');
    }
}
