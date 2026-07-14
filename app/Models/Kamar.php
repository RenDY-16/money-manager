<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Kamar extends Model {
    use SoftDeletes;

    protected $fillable = ['nomor_kamar', 'tipe', 'harga', 'status', 'foto'];

    protected function casts(): array
    {
        return [
            'harga' => 'float',
        ];
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nomor_kamar', 'like', '%' . $search . '%');
    }

    public function penghuni() {
        return $this->hasMany(Penghuni::class);
    }
}
