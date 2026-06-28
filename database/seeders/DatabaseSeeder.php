<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Kost AJ',
            'email' => 'admin@kostaj.com',
            'password' => bcrypt('admin123'),
        ]);

        $kamarData = [
            ['A01', 'single', 600000], ['A02', 'single', 600000], ['A03', 'single', 650000],
            ['A04', 'single', 650000], ['A05', 'single', 700000], ['A06', 'single', 700000],
            ['B01', 'double', 900000], ['B02', 'double', 900000], ['B03', 'double', 950000],
            ['B04', 'double', 950000], ['B05', 'double', 1000000], ['B06', 'double', 1000000],
            ['C01', 'single', 550000], ['C02', 'single', 550000], ['C03', 'single', 600000],
            ['C04', 'single', 600000], ['D01', 'double', 1100000], ['D02', 'double', 1100000],
        ];

        $kamars = collect($kamarData)->map(function ($row, $index) {
            return Kamar::create([
                'nomor_kamar' => $row[0],
                'tipe' => $row[1],
                'harga' => $row[2],
                'status' => $index < 15 ? 'terisi' : 'tersedia',
            ]);
        });

        $penghuniData = [
            ['Budi Santoso', '081234567890', '2026-01-10'],
            ['Andi Wijaya', '085789012345', '2026-01-15'],
            ['Cici Lestari', '082134567812', '2026-02-01'],
            ['Dewi Anggraini', '081345678901', '2026-02-08'],
            ['Eko Prasetyo', '085612345678', '2026-02-20'],
            ['Fajar Maulana', '087765432101', '2026-03-02'],
            ['Gita Permata', '082245678912', '2026-03-11'],
            ['Hendra Saputra', '081987654321', '2026-03-19'],
            ['Intan Maharani', '085234567891', '2026-04-01'],
            ['Joko Nugroho', '082112345678', '2026-04-09'],
            ['Kartika Sari', '081278945612', '2026-04-17'],
            ['Lukman Hakim', '085998877665', '2026-05-03'],
            ['Maya Putri', '082311223344', '2026-05-10'],
            ['Nanda Firmansyah', '081322334455', '2026-05-18'],
            ['Rani Amelia', '085711223344', '2026-06-01'],
        ];

        $penghunis = collect($penghuniData)->map(function ($row, $index) use ($kamars) {
            return Penghuni::create([
                'nama' => $row[0],
                'no_hp' => $row[1],
                'kamar_id' => $kamars[$index]->id,
                'tanggal_masuk' => $row[2],
            ]);
        });

        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = Carbon::now()->subMonthNoOverflow()->startOfMonth();

        foreach ($penghunis as $index => $penghuni) {
            Pemasukan::create([
                'kategori' => 'pembayaran_kost',
                'penghuni_id' => $penghuni->id,
                'jumlah' => optional($penghuni->kamar)->harga ?? 0,
                'tanggal' => $bulanLalu->copy()->addDays(($index % 25) + 1)->toDateString(),
                'keterangan' => 'Bayar kost bulan ' . $bulanLalu->locale('id')->translatedFormat('F Y'),
            ]);
        }

        foreach ($penghunis->take(9) as $index => $penghuni) {
            Pemasukan::create([
                'kategori' => 'pembayaran_kost',
                'penghuni_id' => $penghuni->id,
                'jumlah' => optional($penghuni->kamar)->harga ?? 0,
                'tanggal' => $bulanIni->copy()->addDays($index + 1)->toDateString(),
                'keterangan' => 'Bayar kost bulan ' . $bulanIni->locale('id')->translatedFormat('F Y'),
            ]);
        }


        $pemasukanLainnya = [
            [350000, 'Pemasukan laundry penghuni', 7],
            [150000, 'Denda keterlambatan pembayaran', 11],
            [500000, 'Sewa parkir motor bulanan', 15],
        ];

        foreach ($pemasukanLainnya as $row) {
            Pemasukan::create([
                'kategori' => 'pemasukan_lainnya',
                'penghuni_id' => null,
                'jumlah' => $row[0],
                'tanggal' => $bulanIni->copy()->addDays($row[2])->toDateString(),
                'keterangan' => $row[1],
            ]);
        }

        $pengeluaranData = [
            [150000, 'Listrik', 'Pembelian token listrik utama', 3],
            [85000, 'Air', 'Pembayaran tagihan air PDAM', 5],
            [125000, 'Kebersihan', 'Pembelian alat kebersihan', 6],
            [250000, 'Perbaikan', 'Perbaikan kran kamar mandi', 8],
            [300000, 'Internet', 'Pembayaran internet bulanan', 10],
            [175000, 'Lainnya', 'Pembelian lampu cadangan', 12],
            [425000, 'Perbaikan', 'Servis AC kamar B03', 14],
            [95000, 'Kebersihan', 'Biaya angkut sampah', 16],
            [225000, 'Listrik', 'Tambahan token area luar', 18],
            [110000, 'Air', 'Perawatan pompa air', 20],
        ];

        foreach ($pengeluaranData as $row) {
            Pengeluaran::create([
                'jumlah' => $row[0],
                'tanggal' => $bulanIni->copy()->addDays($row[3])->toDateString(),
                'kategori' => $row[1],
                'keterangan' => $row[2],
            ]);
        }
    }
}
