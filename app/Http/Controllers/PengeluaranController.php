<?php
namespace App\Http\Controllers;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PengeluaranController extends Controller {
    public function index(Request $request){
        $query = Pengeluaran::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $pengeluarans = $query->orderByDesc('tanggal')->orderByDesc('id')->get();
        $totalPengeluaran = (float) Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = (float) Pengeluaran::whereBetween('tanggal', [
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString(),
        ])->sum('jumlah');
        $kategoriList = $this->kategoriList();
        return view('pengeluaran.index', compact('pengeluarans', 'totalPengeluaran', 'pengeluaranBulanIni', 'kategoriList'));
    }

    public function create(){
        $kategoriList = $this->kategoriList();
        return view('pengeluaran.create', compact('kategoriList'));
    }

    public function store(Request $request){
        $data = $this->validatedData($request);
        Pengeluaran::create($data);
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Pengeluaran $pengeluaran){
        $kategoriList = $this->kategoriList();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran){
        $data = $this->validatedData($request);
        $pengeluaran->update($data);
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Pengeluaran $pengeluaran){
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:100',
            'kategori_baru' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi.',
            'jumlah.numeric' => 'Jumlah pengeluaran harus berupa angka.',
            'jumlah.min' => 'Jumlah pengeluaran tidak boleh bernilai negatif.',
            'tanggal.required' => 'Tanggal pengeluaran wajib diisi.',
            'kategori.required' => 'Kategori pengeluaran wajib dipilih.',
            'kategori_baru.required' => 'Nama kategori baru wajib diisi.',
        ]);

        if ($data['kategori'] === '__custom__') {
            $validator = Validator::make($request->all(), [
                'kategori_baru' => 'required|string|max:100',
            ], [
                'kategori_baru.required' => 'Nama kategori baru wajib diisi.',
                'kategori_baru.max' => 'Nama kategori baru maksimal 100 karakter.',
            ]);

            if ($validator->fails()) {
                $validator->validate();
            }

            $data['kategori'] = trim($data['kategori_baru']);
        }

        unset($data['kategori_baru']);

        return $data;
    }

    private function kategoriList(): array
    {
        $defaultCategories = ['Listrik', 'Air', 'Kebersihan', 'Perbaikan', 'Internet', 'Keamanan', 'ATK', 'Lainnya'];
        $storedCategories = Pengeluaran::query()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return collect($defaultCategories)
            ->merge($storedCategories)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
