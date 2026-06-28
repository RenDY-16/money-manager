<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model {
    protected $fillable = ['nomor_kamar', 'tipe', 'harga', 'status'];

    public function penghuni() {
        return $this->hasMany(Penghuni::class);
    }
}
