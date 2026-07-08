<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Penghuni extends Model {
    use SoftDeletes;

    protected $fillable = ['nama', 'no_hp', 'kamar_id', 'tanggal_masuk', 'tanggal_keluar'];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'tanggal_keluar' => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->whereNull('tanggal_keluar');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
                ->orWhere('no_hp', 'like', '%' . $search . '%')
                ->orWhereHas('kamar', fn ($room) => $room->where('nomor_kamar', 'like', '%' . $search . '%'));
        });
    }

    public function kamar() {
        return $this->belongsTo(Kamar::class);
    }

    public function pemasukan() {
        return $this->hasMany(Pemasukan::class);
    }
}
