<?php
namespace App\Http\Controllers;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller {
    public function index(){
        $kamars = Kamar::latest()->get();
        return view('kamar.index', compact('kamars'));
    }

    public function create(){
        return view('kamar.create');
    }

    public function store(Request $request){
        $request->validate([
            'nomor_kamar' => 'required|string',
            'tipe' => 'required|in:single,double',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,terisi',
        ]);
        Kamar::create($request->all());
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Kamar $kamar){
        return view('kamar.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar){
        $request->validate([
            'nomor_kamar' => 'required|string',
            'tipe' => 'required|in:single,double',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,terisi',
        ]);
        $kamar->update($request->all());
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy(Kamar $kamar){
        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus!');
    }
}
