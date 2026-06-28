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
            'no_hp' => ['required', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
        ], $this->validationMessages());
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
            'no_hp' => ['required', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
        ], $this->validationMessages());

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


    private function validationMessages(): array
    {
        return [
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.regex' => 'Nomor telepon hanya boleh berisi angka. Hapus huruf, spasi, tanda plus, atau simbol lain.',
            'no_hp.min' => 'Nomor telepon minimal 10 digit.',
            'no_hp.max' => 'Nomor telepon maksimal 15 digit.',
            'tanggal_keluar.after_or_equal' => 'Tanggal keluar tidak boleh lebih awal dari tanggal masuk.',
        ];
    }
}
