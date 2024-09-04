<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobilRentalImages extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mobil_rental_images';

    protected $fillable = [
        'mobil_rental_id',
        'image_url'
    ];

    public function mobilRental()
    {
        return $this->belongsTo(MobilRental::class, 'mobil_rental_id', 'id');
    }
}
