<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobilRental extends Model
{
    use HasFactory, SoftDeletes;

    // $table->string('nopol');
    // $table->string('type');
    // $table->string('jumlah_kursi');
    // $table->string('fasilitas')->nullable();
    // $table->string('image_url')->nullable();
    // $table->string('mesin')->nullable();
    // $table->string('transmisi')->nullable();
    // $table->string('kapasitas_bagasi')->nullable();
    // $table->string('bahan_bakar')->nullable();

    protected $table = 'mobil_rental';
    protected $fillable = [
        'nopol',
        'type',
        'jumlah_kursi',
        'fasilitas',
        'image_url',
        'mesin',
        'transmisi',
        'kapasitas_bagasi',
        'bahan_bakar',
    ];

    public function rental()
    {
        return $this->hasMany(Rental::class);
    }
}
