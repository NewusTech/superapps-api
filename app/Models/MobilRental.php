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
        'deskripsi',
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

    public function images()
    {
        return $this->hasMany(MobilRentalImages::class, 'mobil_rental_id', 'id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(FasilitasMobilRental::class, 'fasilitas_mobil', 'mobil_rental_id', 'fasilitas_mobil_id');
    }
}
