<?php
namespace App\Http\Controllers;
use App\Models\Pemasukan;
use App\Models\Penghuni;
use Illuminate\Http\Request;

class PemasukanController extends Controller {
    public function index(){
        $pemasukans = Pemasukan::with('penghuni')->latest()->get();
        $totalPemasukan = Pemasukan::sum('jumlah');
        return view('pemasukan.index', compact('pemasukans', 'totalPemasukan'));
    }

    public function create(){
        $penghunis = Penghuni::whereNull('tanggal_keluar')->get();
        return view('pemasukan.create', compact('penghunis'));
    }

    public function store(Request $request){
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);
        Pemasukan::create($request->all());
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit(Pemasukan $pemasukan){
        $penghunis = Penghuni::all();
        return view('pemasukan.edit', compact('pemasukan', 'penghunis'));
    }

    public function update(Request $request, Pemasukan $pemasukan){
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);
        $pemasukan->update($request->all());
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Pemasukan $pemasukan){
        $pemasukan->delete();
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil dihapus!');
    }
}
