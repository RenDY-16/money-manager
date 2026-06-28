<?php
namespace App\Http\Controllers;
use App\Models\Pemasukan;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemasukanController extends Controller {
    public function index(){
        $pemasukans = Pemasukan::with('penghuni')->latest()->get();
        $penghunis = Penghuni::whereNull('tanggal_keluar')->with('kamar')->get();
        $totalPemasukan = (float) Pemasukan::sum('jumlah');
        $pemasukanBulanIni = (float) Pemasukan::all()
            ->filter(fn ($item) => Carbon::parse($item->tanggal)->isSameMonth(Carbon::now()))
            ->sum('jumlah');
        return view('pemasukan.index', compact('pemasukans', 'penghunis', 'totalPemasukan', 'pemasukanBulanIni'));
    }

    public function create(){
        $penghunis = Penghuni::whereNull('tanggal_keluar')->with('kamar')->get();
        return view('pemasukan.create', compact('penghunis'));
    }

    public function store(Request $request){
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
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
            'keterangan' => 'nullable|string|max:255',
        ]);
        $pemasukan->update($request->all());
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Pemasukan $pemasukan){
        $pemasukan->delete();
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil dihapus!');
    }
}
