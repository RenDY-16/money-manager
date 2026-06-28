<?php
namespace App\Http\Controllers;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenghuniController extends Controller {
    public function index(Request $request){
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $penghuniLunasIds = Pemasukan::where('kategori', 'pembayaran_kost')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->whereNotNull('penghuni_id')
            ->pluck('penghuni_id')
            ->unique()
            ->all();

        $query = Penghuni::with('kamar');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    ->orWhereHas('kamar', fn ($room) => $room->where('nomor_kamar', 'like', '%' . $search . '%'));
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->whereNull('tanggal_keluar');
            } elseif ($request->status === 'keluar') {
                $query->whereNotNull('tanggal_keluar');
            }
        }

        if ($request->filled('pembayaran')) {
            if ($request->pembayaran === 'lunas') {
                $query->whereIn('id', $penghuniLunasIds);
            } elseif ($request->pembayaran === 'belum_lunas') {
                $query->whereNull('tanggal_keluar')->whereNotIn('id', $penghuniLunasIds);
            }
        }

        $penghunis = $query->latest()->get()->map(function ($penghuni) use ($penghuniLunasIds) {
            $penghuni->status_pembayaran_bulan_ini = in_array($penghuni->id, $penghuniLunasIds, true)
                ? 'lunas'
                : 'belum_lunas';
            return $penghuni;
        });

        $allPenghunis = Penghuni::with('kamar')->get()->map(function ($penghuni) use ($penghuniLunasIds) {
            $penghuni->status_pembayaran_bulan_ini = in_array($penghuni->id, $penghuniLunasIds, true)
                ? 'lunas'
                : 'belum_lunas';
            return $penghuni;
        });
        $periodeTagihan = Carbon::now()->locale('id')->translatedFormat('F Y');

        return view('penghuni.index', compact('penghunis', 'allPenghunis', 'periodeTagihan'));
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

        if ($penghuni->kamar_id != $request->kamar_id) {
            Kamar::find($penghuni->kamar_id)?->update(['status' => 'tersedia']);
            Kamar::find($request->kamar_id)?->update(['status' => 'terisi']);
        }

        if ($request->filled('tanggal_keluar')) {
            Kamar::find($request->kamar_id)?->update(['status' => 'tersedia']);
        } else {
            Kamar::find($request->kamar_id)?->update(['status' => 'terisi']);
        }

        $penghuni->update($request->all());
        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil diperbarui!');
    }

    public function destroy(Penghuni $penghuni){
        Kamar::find($penghuni->kamar_id)?->update(['status' => 'tersedia']);
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
