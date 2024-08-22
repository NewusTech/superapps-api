<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'waktu_tiba',
        'tanggal_berangkat',
        'ketersedian',
        'available_seats'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($jadwal) {
            $mobil = MasterMobil::find($jadwal->master_mobil_id);
            for ($i = 1; $i <= $mobil->jumlah_kursi; $i++) {
                $jadwal->kursi()->create([
                    'master_mobil_id' => $mobil->id,
                    'jadwal_id' => $jadwal->id,
                    'nomor_kursi' => $i,
                    'status' => 'kosong',
                ]);
            }
        });
        static::created(function ($jadwal){
            $jadwal->available_seats = $jadwal->kursi()->count();
            $jadwal->save();
        });
    }

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
    public function kursi() : HasMany
    {
        return $this->hasMany(Kursi::class, 'jadwal_id', 'id');
    }
}
