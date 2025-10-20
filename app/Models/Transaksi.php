<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // nama tabel di database
    protected $fillable = [
        'pembeli_id',
        'karya_id',
        'tanggal_transaksi',
        'total_harga',
        'status_pembayaran'
    ];

    // Relasi ke Pembeli (many-to-one)
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    // Relasi ke Karya Seni (many-to-one)
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'karya_id');
    }
}
