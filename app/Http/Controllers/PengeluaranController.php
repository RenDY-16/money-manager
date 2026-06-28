<?php
namespace App\Http\Controllers;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengeluaranController extends Controller {
    public function index(){
        $pengeluarans = Pengeluaran::latest()->get();
        $totalPengeluaran = (float) Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = (float) Pengeluaran::all()
            ->filter(fn ($item) => Carbon::parse($item->tanggal)->isSameMonth(Carbon::now()))
            ->sum('jumlah');
        $kategoriList = ['Listrik', 'Air', 'Kebersihan', 'Perbaikan', 'Internet', 'Lainnya'];
        return view('pengeluaran.index', compact('pengeluarans', 'totalPengeluaran', 'pengeluaranBulanIni', 'kategoriList'));
    }

    public function create(){
        $kategoriList = ['Listrik', 'Air', 'Kebersihan', 'Perbaikan', 'Internet', 'Lainnya'];
        return view('pengeluaran.create', compact('kategoriList'));
    }

    public function store(Request $request){
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);
        Pengeluaran::create($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Pengeluaran $pengeluaran){
        $kategoriList = ['Listrik', 'Air', 'Kebersihan', 'Perbaikan', 'Internet', 'Lainnya'];
        return view('pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran){
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $pengeluaran->update($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Pengeluaran $pengeluaran){
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
