<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobilTravelImages extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mobil_travel_images';

    protected $fillable = [
        'master_mobil_id',
        'image_url'
    ];

    public function masterMobil()
    {
        return $this->belongsTo(MasterMobil::class, 'master_mobil_id', 'id');
    }
}
