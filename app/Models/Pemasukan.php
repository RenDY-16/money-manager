<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model {
    protected $fillable = ['kategori', 'penghuni_id', 'jumlah', 'tanggal', 'keterangan'];

    public function penghuni() {
        return $this->belongsTo(Penghuni::class);
    }
}
