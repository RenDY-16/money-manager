<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function restoreJson(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        $file = $request->file('backup_file');
        $json = json_decode(file_get_contents($file->getRealPath()), true);

        if (!$json || !isset($json['application']) || $json['application'] !== 'Kost AJ Lanraki' || !isset($json['tables'])) {
            return back()->withErrors(['backup_file' => 'Format file backup tidak valid. Harus berupa JSON backup asli Kost AJ Lanraki.']);
        }

        $tables = $json['tables'];

        try {
            DB::transaction(function () use ($tables) {
                Schema::disableForeignKeyConstraints();

                // Truncate
                Kamar::truncate();
                Penghuni::truncate();
                Pemasukan::truncate();
                Pengeluaran::truncate();

                // 1. Restore Kamars
                if (isset($tables['kamars'])) {
                    foreach ($tables['kamars'] as $row) {
                        Kamar::create([
                            'id' => $row['id'],
                            'nomor_kamar' => $row['nomor_kamar'],
                            'tipe' => $row['tipe'] ?? 'single',
                            'harga' => $row['harga'],
                            'status' => $row['status'] ?? 'tersedia',
                            'created_at' => $row['created_at'] ?? null,
                            'updated_at' => $row['updated_at'] ?? null,
                        ]);
                    }
                }

                // 2. Restore Penghunis
                if (isset($tables['penghunis'])) {
                    foreach ($tables['penghunis'] as $row) {
                        Penghuni::create([
                            'id' => $row['id'],
                            'nama' => $row['nama'],
                            'no_hp' => $row['no_hp'],
                            'kamar_id' => $row['kamar_id'],
                            'tanggal_masuk' => $row['tanggal_masuk'],
                            'tanggal_keluar' => $row['tanggal_keluar'] ?? null,
                            'created_at' => $row['created_at'] ?? null,
                            'updated_at' => $row['updated_at'] ?? null,
                        ]);
                    }
                }

                // 3. Restore Pemasukans
                if (isset($tables['pemasukans'])) {
                    foreach ($tables['pemasukans'] as $row) {
                        Pemasukan::create([
                            'id' => $row['id'],
                            'kategori' => $row['kategori'] ?? 'pembayaran_kost',
                            'penghuni_id' => $row['penghuni_id'] ?? null,
                            'jumlah' => $row['jumlah'],
                            'tanggal' => $row['tanggal'],
                            'keterangan' => $row['keterangan'] ?? null,
                            'created_at' => $row['created_at'] ?? null,
                            'updated_at' => $row['updated_at'] ?? null,
                        ]);
                    }
                }

                // 4. Restore Pengeluarans
                if (isset($tables['pengeluarans'])) {
                    foreach ($tables['pengeluarans'] as $row) {
                        Pengeluaran::create([
                            'id' => $row['id'],
                            'jumlah' => $row['jumlah'],
                            'tanggal' => $row['tanggal'],
                            'kategori' => $row['kategori'],
                            'keterangan' => $row['keterangan'] ?? null,
                            'created_at' => $row['created_at'] ?? null,
                            'updated_at' => $row['updated_at'] ?? null,
                        ]);
                    }
                }

                Schema::enableForeignKeyConstraints();
            });

            ActivityLog::log('Restore Backup', "Melakukan restore data dari file: " . $file->getClientOriginalName());

            return redirect()->route('backup.index')->with('success', 'Database berhasil di-restore dari backup JSON!');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            return back()->withErrors(['backup_file' => 'Gagal melakukan restore: ' . $e->getMessage()]);
        }
    }
}
