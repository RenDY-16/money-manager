<?php
namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemasukanController extends Controller {
    public function index(Request $request){
        $query = Pemasukan::with('penghuni.kamar');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                    ->orWhereHas('penghuni', fn ($tenant) => $tenant->where('nama', 'like', '%' . $search . '%'));
            });
        }

        if ($request->filled('kategori') && in_array($request->kategori, ['pembayaran_kost', 'pemasukan_lainnya'], true)) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $pemasukans = $query->orderByDesc('tanggal')->orderByDesc('id')->get();
        $penghunis = Penghuni::whereNull('tanggal_keluar')->with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();
        $totalPemasukan = (float) Pemasukan::sum('jumlah');
        $pemasukanBulanIni = (float) Pemasukan::whereBetween('tanggal', [
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString(),
        ])->sum('jumlah');

        $periodeTagihan = Carbon::now()->locale('id')->translatedFormat('F Y');
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $penghuniSudahBayarIds = Pemasukan::where('kategori', 'pembayaran_kost')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->whereNotNull('penghuni_id')
            ->pluck('penghuni_id')
            ->unique()
            ->all();

        $penghuniBelumBayar = Penghuni::whereNull('tanggal_keluar')
            ->whereNotIn('id', $penghuniSudahBayarIds)
            ->with('kamar')
            ->orderBy('nama')
            ->get()
            ->map(function ($penghuni) use ($periodeTagihan) {
                $nomorKamar = optional($penghuni->kamar)->nomor_kamar ?: '-';
                $hargaKamar = (float) optional($penghuni->kamar)->harga;
                $message = "Halo {$penghuni->nama}, kami ingin mengingatkan pembayaran kost untuk bulan {$periodeTagihan}. Mohon melakukan pembayaran kamar {$nomorKamar} sebesar Rp " . number_format($hargaKamar, 0, ',', '.') . ". Jika sudah membayar, abaikan pesan ini. Terima kasih.";

                $penghuni->wa_number = $this->normalizeWhatsappNumber($penghuni->no_hp);
                $penghuni->wa_message = $message;
                $penghuni->wa_link = $penghuni->wa_number
                    ? 'https://wa.me/' . $penghuni->wa_number . '?text=' . rawurlencode($message)
                    : null;

                return $penghuni;
            });

        $jumlahPenghuniAktif = Penghuni::whereNull('tanggal_keluar')->count();
        $jumlahLunas = count($penghuniSudahBayarIds);
        $jumlahBelumLunas = $penghuniBelumBayar->count();

        return view('pemasukan.index', compact(
            'pemasukans', 'penghunis', 'kategoriPemasukan', 'totalPemasukan', 'pemasukanBulanIni',
            'penghuniBelumBayar', 'periodeTagihan', 'jumlahPenghuniAktif', 'jumlahLunas', 'jumlahBelumLunas'
        ));
    }

    public function create(){
        $penghunis = Penghuni::whereNull('tanggal_keluar')->with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();
        return view('pemasukan.create', compact('penghunis', 'kategoriPemasukan'));
    }

    public function store(Request $request){
        $data = $this->validatedData($request);
        Pemasukan::create($data);
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit(Pemasukan $pemasukan){
        $penghunis = Penghuni::with('kamar')->orderBy('nama')->get();
        $kategoriPemasukan = $this->kategoriPemasukan();
        return view('pemasukan.edit', compact('pemasukan', 'penghunis', 'kategoriPemasukan'));
    }

    public function update(Request $request, Pemasukan $pemasukan){
        $data = $this->validatedData($request);
        $pemasukan->update($data);
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Pemasukan $pemasukan){
        $pemasukan->delete();
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil dihapus!');
    }

    private function validatedData(Request $request): array
    {
        $baseRules = [
            'kategori' => 'required|in:pembayaran_kost,pemasukan_lainnya',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ];

        $baseMessages = [
            'kategori.required' => 'Kategori pemasukan wajib dipilih.',
            'kategori.in' => 'Kategori pemasukan tidak valid.',
            'tanggal.required' => 'Tanggal pemasukan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
        ];

        $data = $request->validate($baseRules, $baseMessages);

        if ($request->kategori === 'pembayaran_kost') {
            $paymentData = $request->validate([
                'penghuni_id' => 'required|exists:penghunis,id',
                'jumlah' => 'nullable|numeric|min:0',
            ], [
                'penghuni_id.required' => 'Penghuni wajib dipilih untuk kategori pembayaran kost.',
                'penghuni_id.exists' => 'Data penghuni tidak ditemukan.',
                'jumlah.numeric' => 'Jumlah pemasukan harus berupa angka.',
                'jumlah.min' => 'Jumlah pemasukan tidak boleh bernilai negatif.',
            ]);

            $penghuni = Penghuni::with('kamar')->findOrFail($paymentData['penghuni_id']);
            $data['penghuni_id'] = $paymentData['penghuni_id'];
            $data['jumlah'] = $request->filled('jumlah')
                ? (float) $paymentData['jumlah']
                : (float) optional($penghuni->kamar)->harga;

            if (! $request->filled('keterangan')) {
                $periode = Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('F Y');
                $data['keterangan'] = 'Pembayaran kost bulan ' . $periode;
            }

            return $data;
        }

        $otherIncomeData = $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ], [
            'jumlah.required' => 'Jumlah pemasukan lainnya wajib diisi.',
            'jumlah.numeric' => 'Jumlah pemasukan harus berupa angka.',
            'jumlah.min' => 'Jumlah pemasukan tidak boleh bernilai negatif.',
        ]);

        $data['penghuni_id'] = null;
        $data['jumlah'] = (float) $otherIncomeData['jumlah'];

        return $data;
    }

    private function kategoriPemasukan(): array
    {
        return [
            'pembayaran_kost' => 'Pembayaran Kost',
            'pemasukan_lainnya' => 'Pemasukan Lainnya',
        ];
    }

    private function normalizeWhatsappNumber(?string $phone): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $phone);

        if ($number === '') {
            return null;
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '62')) {
            return $number;
        }

        return '62' . $number;
    }
}
