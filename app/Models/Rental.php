<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rental';
    protected $fillable = [
        'durasi_sewa',
        'area',
        'tanggal_mulai_sewa',
        'tanggal_akhir_sewa',
        'alamat_keberangkatan',
        'nama',
        'email',
        'mobil_rental_id',
        'nik',
        'no_telp',
        'alamat',
        'metode_id',
        'all_in',
    ];

    public function mobil(){
        return $this->belongsTo(MobilRental::class, 'mobil_rental_id', 'id');
    }
}
