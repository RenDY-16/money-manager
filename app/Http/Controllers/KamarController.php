<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\ActivityLog;
use App\Http\Requests\StoreKamarRequest;
use App\Http\Requests\UpdateKamarRequest;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::query();

        if ($request->filled('search')) {
            $query->search(trim($request->search));
        }

        if ($request->filled('status') && in_array($request->status, ['tersedia', 'terisi'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe') && in_array($request->tipe, ['single', 'double'], true)) {
            $query->where('tipe', $request->tipe);
        }

        // Paginate for performance
        $kamars = $query->orderBy('nomor_kamar')->paginate(10)->withQueryString();

        // Load stats using SQL aggregates instead of loading all models
        $totalKamarCount = Kamar::count();
        $totalKamarTersedia = Kamar::where('status', 'tersedia')->count();
        $totalKamarTerisi = Kamar::where('status', 'terisi')->count();
        $totalKamarPotensi = Kamar::sum('harga');

        return view('kamar.index', compact(
            'kamars',
            'totalKamarCount',
            'totalKamarTersedia',
            'totalKamarTerisi',
            'totalKamarPotensi'
        ));
    }

    public function create()
    {
        return view('kamar.create');
    }

    public function store(StoreKamarRequest $request)
    {
        $kamar = Kamar::create($request->validated());

        ActivityLog::log('Tambah Kamar', "Menambahkan kamar nomor {$kamar->nomor_kamar} dengan harga " . $kamar->formatted_harga);

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Kamar $kamar)
    {
        return view('kamar.edit', compact('kamar'));
    }

    public function update(UpdateKamarRequest $request, Kamar $kamar)
    {
        $oldNomor = $kamar->nomor_kamar;
        $kamar->update($request->validated());

        ActivityLog::log('Update Kamar', "Memperbarui kamar nomor {$oldNomor} menjadi {$kamar->nomor_kamar}");

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy(Kamar $kamar)
    {
        $nomor = $kamar->nomor_kamar;
        $kamar->delete();

        ActivityLog::log('Hapus Kamar', "Menghapus kamar nomor {$nomor} (Soft Delete)");

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus!');
    }
}
