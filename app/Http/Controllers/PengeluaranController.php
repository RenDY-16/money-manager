<?php
namespace App\Http\Controllers;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller {
    public function index(){
        $pengeluarans = Pengeluaran::latest()->get();
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        return view('pengeluaran.index', compact('pengeluarans', 'totalPengeluaran'));
    }

    public function create(){
        return view('pengeluaran.create');
    }

    public function store(Request $request){
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
        ]);
        Pengeluaran::create($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Pengeluaran $pengeluaran){
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran){
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
        ]);
        $pengeluaran->update($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Pengeluaran $pengeluaran){
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
