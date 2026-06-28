<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model {
    protected $fillable = ['nama', 'no_hp', 'kamar_id', 'tanggal_masuk', 'tanggal_keluar'];

    public function kamar() {
        return $this->belongsTo(Kamar::class);
    }

    public function pemasukan() {
        return $this->hasMany(Pemasukan::class);
    }
}
