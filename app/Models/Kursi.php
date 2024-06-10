<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kursi extends Model
{
    use HasFactory;

    protected $table = 'kursi';
    protected $fillable = [
        'master_mobil_id',
        'status',
        'nomor_kursi'
    ];


    public function mobil()
    {
        return $this->belongsTo(MasterMobil::class, 'master_mobil_id');
    }
}
