<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobilRental extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'mobil_rental';
    protected $fillable = [
        'nopol',
        'type',
        'jumlah_kursi',
        'fasilitas',
        'image_url',
        'biaya_sewa',
        'mesin',
        'transmisi',
        'kapasitas_bagasi',
        'bahan_bakar',
        'biaya_all_in',
    ];

    public function rental()
    {
        return $this->hasMany(Rental::class);
    }
}
