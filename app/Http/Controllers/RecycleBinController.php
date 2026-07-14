<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'kamar');

        $deletedKamar = Kamar::onlyTrashed()->latest('deleted_at')->get();
        $deletedPenghuni = Penghuni::onlyTrashed()->with('kamar')->latest('deleted_at')->get();
        $deletedPemasukan = Pemasukan::onlyTrashed()->with('penghuni')->latest('deleted_at')->get();
        $deletedPengeluaran = Pengeluaran::onlyTrashed()->latest('deleted_at')->get();

        $counts = [
            'kamar' => $deletedKamar->count(),
            'penghuni' => $deletedPenghuni->count(),
            'pemasukan' => $deletedPemasukan->count(),
            'pengeluaran' => $deletedPengeluaran->count(),
        ];

        return view('recycle-bin.index', compact(
            'deletedKamar', 'deletedPenghuni', 'deletedPemasukan', 'deletedPengeluaran', 'counts', 'tab'
        ));
    }

    public function restore(Request $request, string $type, int $id)
    {
        $model = $this->resolveModel($type, $id);

        if (!$model) {
            return back()->withErrors(['error' => 'Data tidak ditemukan.']);
        }

        $model->restore();
        ActivityLog::log('Restore Data', "Memulihkan {$type} ID:{$id} dari recycle bin.");

        return redirect()->route('recycle-bin.index', ['tab' => $type])->with('success', ucfirst($type) . ' berhasil dipulihkan!');
    }

    public function forceDelete(Request $request, string $type, int $id)
    {
        $model = $this->resolveModel($type, $id);

        if (!$model) {
            return back()->withErrors(['error' => 'Data tidak ditemukan.']);
        }

        $model->forceDelete();
        ActivityLog::log('Hapus Permanen', "Menghapus permanen {$type} ID:{$id}.");

        return redirect()->route('recycle-bin.index', ['tab' => $type])->with('success', ucfirst($type) . ' berhasil dihapus permanen!');
    }

    private function resolveModel(string $type, int $id)
    {
        return match ($type) {
            'kamar' => Kamar::onlyTrashed()->find($id),
            'penghuni' => Penghuni::onlyTrashed()->find($id),
            'pemasukan' => Pemasukan::onlyTrashed()->find($id),
            'pengeluaran' => Pengeluaran::onlyTrashed()->find($id),
            default => null,
        };
    }
}
