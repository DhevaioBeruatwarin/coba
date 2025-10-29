<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // nama tabel di database
    protected $primaryKey = 'no_transaksi';

    protected $fillable = [
        'id_pembeli',    // harus sesuai migrasi
        'kode_seni',     // harus sesuai migrasi
        'tanggal_jual',
        'harga'
    ];

    // Relasi ke Pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    // Relasi ke KaryaSeni
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'kode_seni', 'kode_seni');
    }
}
