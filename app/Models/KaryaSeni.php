<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryaSeni extends Model
{
    protected $table = 'karya_seni';
    protected $primaryKey = 'kode_seni';
    public $incrementing = false; // karena kode_seni bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'kode_seni',
        'nama_karya',
        'harga',
        'id_seniman'
    ];

    // Relasi ke seniman (setiap karya milik satu seniman)
    public function seniman()
    {
        return $this->belongsTo(Seniman::class, 'id_seniman', 'id_seniman');
    }

    // Relasi ke transaksi (karya bisa dijual dalam banyak transaksi)
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kode_seni', 'kode_seni');
    }
}
