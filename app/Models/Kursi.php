<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kursi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kursi';
    protected $fillable = [
        'master_mobil_id',
        'jadwal_id',
        'status',
        'nomor_kursi'
    ];


    public function mobil()
    {
        return $this->belongsTo(MasterMobil::class, 'master_mobil_id');
    }
    public static function boot()
    {
        parent::boot();
        self::updated(function ($kursi) {
            if ($kursi->isDirty('status')) {
                $jadwal = $kursi->jadwal;
                $availableSeats = $jadwal->kursi()->where('status', 'kosong')->count();
                $jadwal->available_seats = $availableSeats;
                $jadwal->save();
            }
        });
    }
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'id');
    }
}
