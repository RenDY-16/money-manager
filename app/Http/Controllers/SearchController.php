<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%' . $q . '%';

        $kamar = Kamar::where('nomor_kamar', 'like', $like)
            ->limit(5)
            ->get()
            ->map(fn($k) => [
                'title' => 'Kamar ' . $k->nomor_kamar,
                'subtitle' => ucfirst($k->tipe) . ' — Rp ' . number_format($k->harga, 0, ',', '.') . ' — ' . ucfirst($k->status),
                'url' => route('kamar.index', ['search' => $k->nomor_kamar]),
            ]);

        $penghuni = Penghuni::with('kamar')
            ->where(function ($query) use ($like) {
                $query->where('nama', 'like', $like)
                    ->orWhere('no_hp', 'like', $like);
            })
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title' => $p->nama,
                'subtitle' => 'HP: ' . $p->no_hp . ' — Kamar ' . (optional($p->kamar)->nomor_kamar ?? '-'),
                'url' => route('penghuni.show', $p->id),
            ]);

        $pemasukan = Pemasukan::with('penghuni')
            ->where(function ($query) use ($like) {
                $query->where('keterangan', 'like', $like)
                    ->orWhereHas('penghuni', fn($t) => $t->where('nama', 'like', $like));
            })
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title' => 'Rp ' . number_format($p->jumlah, 0, ',', '.'),
                'subtitle' => (optional($p->penghuni)->nama ?? 'Lainnya') . ' — ' . $p->tanggal->format('d M Y'),
                'url' => route('pemasukan.index', ['search' => trim($q)]),
            ]);

        $pengeluaran = Pengeluaran::where(function ($query) use ($like) {
                $query->where('kategori', 'like', $like)
                    ->orWhere('keterangan', 'like', $like);
            })
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title' => 'Rp ' . number_format($p->jumlah, 0, ',', '.'),
                'subtitle' => $p->kategori . ' — ' . $p->tanggal->format('d M Y'),
                'url' => route('pengeluaran.index', ['search' => trim($q)]),
            ]);

        return response()->json([
            'kamar' => $kamar,
            'penghuni' => $penghuni,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
        ]);
    }
}
