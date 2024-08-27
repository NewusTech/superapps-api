<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penginapan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penginapan';
    protected $fillable = [
        'title', 'lokasi', 'jumlah_kamar', 'luas_ruangan', 'rating', 'harga', 'tipe', 'status'
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_penginapan', 'penginapan_id', 'fasilitas_id');
    }

    public function kebijakan()
    {
    return $this->belongsToMany(Kebijakan::class, 'kebijakan_penginapan', 'penginapan_id', 'kebijakan_id');
    }
}
