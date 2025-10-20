<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seniman extends Model
{
    protected $table = 'seniman';
    protected $primaryKey = 'id_seniman';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'no_hp'
    ];

    protected $hidden = [
        'password',
    ];

    // relasi: seorang seniman bisa punya banyak karya
    public function karya()
    {
        return $this->hasMany(KaryaSeni::class, 'id_seniman', 'id_seniman');
    }
}
