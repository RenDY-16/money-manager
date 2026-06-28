<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed User
        User::factory()->create([
            'name' => 'Admin Kost AJ',
            'email' => 'admin@kostaj.com',
            'password' => bcrypt('admin123'),
        ]);

        // 2. Seed Kamar
        $kamarA01 = Kamar::create(['nomor_kamar' => 'A01', 'tipe' => 'single', 'harga' => 600000, 'status' => 'terisi']);
        $kamarA02 = Kamar::create(['nomor_kamar' => 'A02', 'tipe' => 'single', 'harga' => 600000, 'status' => 'tersedia']);
        $kamarB01 = Kamar::create(['nomor_kamar' => 'B01', 'tipe' => 'double', 'harga' => 950000, 'status' => 'terisi']);
        $kamarB02 = Kamar::create(['nomor_kamar' => 'B02', 'tipe' => 'double', 'harga' => 950000, 'status' => 'tersedia']);
        $kamarC01 = Kamar::create(['nomor_kamar' => 'C01', 'tipe' => 'single', 'harga' => 500000, 'status' => 'terisi']);

        // 3. Seed Penghuni
        $budi = Penghuni::create([
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'kamar_id' => $kamarA01->id,
            'tanggal_masuk' => '2026-01-10',
        ]);

        $andi = Penghuni::create([
            'nama' => 'Andi Wijaya',
            'no_hp' => '085789012345',
            'kamar_id' => $kamarB01->id,
            'tanggal_masuk' => '2026-02-15',
        ]);

        $cici = Penghuni::create([
            'nama' => 'Cici Lestari',
            'no_hp' => '082134567812',
            'kamar_id' => $kamarC01->id,
            'tanggal_masuk' => '2026-03-01',
        ]);

        // 4. Seed Pemasukan
        Pemasukan::create([
            'penghuni_id' => $budi->id,
            'jumlah' => 600000,
            'tanggal' => '2026-06-01',
            'keterangan' => 'Bayar kost bulan Juni 2026',
        ]);
        Pemasukan::create([
            'penghuni_id' => $andi->id,
            'jumlah' => 950000,
            'tanggal' => '2026-06-02',
            'keterangan' => 'Bayar kost bulan Juni 2026',
        ]);
        Pemasukan::create([
            'penghuni_id' => $cici->id,
            'jumlah' => 500000,
            'tanggal' => '2026-06-05',
            'keterangan' => 'Bayar kost bulan Juni 2026',
        ]);

        // 5. Seed Pengeluaran
        Pengeluaran::create([
            'jumlah' => 150000,
            'tanggal' => '2026-06-10',
            'kategori' => 'Listrik',
            'keterangan' => 'Pembelian token listrik utama',
        ]);
        Pengeluaran::create([
            'jumlah' => 75000,
            'tanggal' => '2026-06-12',
            'kategori' => 'Air',
            'keterangan' => 'Pembayaran tagihan air PDAM',
        ]);
        Pengeluaran::create([
            'jumlah' => 120000,
            'tanggal' => '2026-06-18',
            'kategori' => 'Perbaikan',
            'keterangan' => 'Servis AC Kamar B01',
        ]);
    }
}
