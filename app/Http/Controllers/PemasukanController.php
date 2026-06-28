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

        $periodeTagihan = Carbon::now()->locale('id')->translatedFormat('F Y');
        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();
        $penghuniSudahBayarIds = Pemasukan::whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->pluck('penghuni_id')
            ->unique()
            ->all();

        $penghuniBelumBayar = Penghuni::whereNull('tanggal_keluar')
            ->whereNotIn('id', $penghuniSudahBayarIds)
            ->with('kamar')
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

        return view('pemasukan.index', compact(
            'pemasukans', 'penghunis', 'totalPemasukan', 'pemasukanBulanIni',
            'penghuniBelumBayar', 'periodeTagihan'
        ));
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
