<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'nota'; // nama tabel di database
    protected $fillable = [
        'transaksi_id',
        'nomor_nota',
        'tanggal_nota',
        'total_harga'
    ];

    // Relasi ke transaksi (many-to-one)
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
