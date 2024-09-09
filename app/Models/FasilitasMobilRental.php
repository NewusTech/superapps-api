<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FasilitasMobilRental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasilitas_mobil_rental';

    protected $fillable = [
        'nama'
    ];

    public function mobil()
    {
        return $this->belongsToMany(MobilRental::class, 'fasilitas_mobil', 'fasilitas_mobil_id', 'mobil_rental_id');
    }
}
