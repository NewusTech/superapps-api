<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal';
    protected $fillable = [
        'master_rute_id',
        'master_mobil_id',
        'master_supir_id',
        'waktu_keberangkatan',
        'tanggal_berangkat',
        'ketersedian',
    ];

    public function master_rute()
    {
        return $this->belongsTo(MasterRute::class, 'master_rute_id', 'id');
    }

    public function master_mobil()
    {
        return $this->belongsTo(MasterMobil::class, 'master_mobil_id', 'id');
    }

    public function master_supir()
    {
        return $this->belongsTo(MasterSupir::class, 'master_supir_id', 'id');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pesanan::class, 'jadwal_id', 'id');
    }
}
