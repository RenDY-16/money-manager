<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model {
    use SoftDeletes;

    protected $fillable = ['kategori', 'penghuni_id', 'jumlah', 'tanggal', 'keterangan'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'float',
        ];
    }

    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('keterangan', 'like', '%' . $search . '%')
                ->orWhereHas('penghuni', fn ($tenant) => $tenant->where('nama', 'like', '%' . $search . '%'));
        });
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeDateRange($query, $start, $end)
    {
        if ($start) {
            $query->whereDate('tanggal', '>=', $start);
        }
        if ($end) {
            $query->whereDate('tanggal', '<=', $end);
        }
        return $query;
    }

    public function penghuni() {
        return $this->belongsTo(Penghuni::class);
    }
}
