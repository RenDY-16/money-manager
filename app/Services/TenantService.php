<?php

namespace App\Services;

use App\Models\Penghuni;
use App\Models\Pemasukan;
use Carbon\Carbon;

class TenantService
{
    /**
     * Get IDs of tenants who have paid their rent for the current billing period.
     */
    public function getLunasPenghuniIds(?string $start = null, ?string $end = null): array
    {
        $start = $start ?? Carbon::now()->startOfMonth()->toDateString();
        $end = $end ?? Carbon::now()->endOfMonth()->toDateString();

        return Pemasukan::where('kategori', 'pembayaran_kost')
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('penghuni_id')
            ->pluck('penghuni_id')
            ->unique()
            ->all();
    }

    /**
     * Get list of active tenants who have not paid their rent for the current period,
     * including their pre-generated WhatsApp reminder messages and links.
     */
    public function getBelumBayarPenghunis(string $periodeTagihan): \Illuminate\Support\Collection
    {
        $lunasIds = $this->getLunasPenghuniIds();

        return Penghuni::active()
            ->whereNotIn('id', $lunasIds)
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

                // Hitung sisa hari jatuh tempo (misal jatuh tempo di tanggal masuk di bulan berjalan)
                $hariMasuk = $penghuni->tanggal_masuk->day;
                $jatuhTempoBulanIni = Carbon::now()->day($hariMasuk)->startOfDay();
                $diff = Carbon::now()->startOfDay()->diffInDays($jatuhTempoBulanIni, false);
                $penghuni->hari_jatuh_tempo = $diff;

                return $penghuni;
            });
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
