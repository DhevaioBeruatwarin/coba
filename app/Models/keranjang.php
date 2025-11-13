<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';

    protected $fillable = [
        'id_pembeli',
        'kode_seni',
        'jumlah'
    ];

    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'kode_seni', 'kode_seni');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }
}
