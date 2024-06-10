<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRute extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_rute';
    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'waktu_keberangkatan',
        'harga'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_rute_id', 'id');
    }
}
