<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['karya_id', 'pembeli_id', 'rating', 'komentar'];

    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'karya_id');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }
}
