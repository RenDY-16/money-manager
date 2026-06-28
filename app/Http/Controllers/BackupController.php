<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        $summary = [
            'kamar' => Kamar::count(),
            'penghuni' => Penghuni::count(),
            'pemasukan' => Pemasukan::count(),
            'pengeluaran' => Pengeluaran::count(),
            'admin' => User::count(),
        ];

        $lastUpdated = collect([
            Kamar::max('updated_at'),
            Penghuni::max('updated_at'),
            Pemasukan::max('updated_at'),
            Pengeluaran::max('updated_at'),
            User::max('updated_at'),
        ])->filter()->max();

        return view('backup.index', compact('summary', 'lastUpdated'));
    }

    public function downloadJson(Request $request)
    {
        $payload = [
            'application' => 'Kost AJ Lanraki',
            'generated_at' => now()->toDateTimeString(),
            'tables' => [
                'users' => User::select('id', 'name', 'email', 'profile_photo', 'created_at', 'updated_at')->get(),
                'kamars' => Kamar::orderBy('nomor_kamar')->get(),
                'penghunis' => Penghuni::with('kamar')->orderBy('nama')->get(),
                'pemasukans' => Pemasukan::with('penghuni.kamar')->orderByDesc('tanggal')->get(),
                'pengeluarans' => Pengeluaran::orderByDesc('tanggal')->get(),
            ],
        ];

        $filename = 'backup-kost-aj-' . now()->format('Ymd-His') . '.json';

        return response()->json($payload, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
