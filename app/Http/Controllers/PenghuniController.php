<?php
namespace App\Http\Controllers;
use App\Models\Penghuni;
use App\Models\Kamar;
use Illuminate\Http\Request;

class PenghuniController extends Controller {
    public function index(){
        $penghunis = Penghuni::with('kamar')->latest()->get();
        return view('penghuni.index', compact('penghunis'));
    }

    public function create(){
        $kamars = Kamar::where('status', 'tersedia')->get();
        return view('penghuni.create', compact('kamars'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string',
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
        ]);
        Penghuni::create($request->all());
        Kamar::find($request->kamar_id)->update(['status' => 'terisi']);
        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil ditambahkan!');
    }

    public function edit(Penghuni $penghuni){
        $kamars = Kamar::all();
        return view('penghuni.edit', compact('penghuni', 'kamars'));
    }

    public function update(Request $request, Penghuni $penghuni){
        $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string',
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
        ]);

        // If kamar changed, update old kamar status
        if ($penghuni->kamar_id != $request->kamar_id) {
            Kamar::find($penghuni->kamar_id)->update(['status' => 'tersedia']);
            Kamar::find($request->kamar_id)->update(['status' => 'terisi']);
        }

        $penghuni->update($request->all());
        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil diperbarui!');
    }

    public function destroy(Penghuni $penghuni){
        Kamar::find($penghuni->kamar_id)->update(['status' => 'tersedia']);
        $penghuni->delete();
        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil dihapus!');
    }
}
