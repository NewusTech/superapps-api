<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMobil extends Model
{
    use HasFactory;
    protected $table = 'master_mobil';
    protected $fillable = [
        'nopol',
        'type',
        'jumlah_kursi',
        'status',
        'image_url',
        'available_seats'
    ];

    public static function boot(){
        parent::boot();

        static::creating(function ($mobil) {
            $mobil->available_seats = $mobil->jumlah_kursi;
        });
        static::created(function ($mobil) {
            for ($i = 1; $i <= $mobil->jumlah_kursi; $i++) {
                $mobil->kursi()->create([
                    'master_mobil_id' => $mobil->id,
                    'nomor_kursi' => $i,
                    'status' => 'kosong',
                ]);
            }
        });
    }

    public function kursi()
    {
        return $this->hasMany(Kursi::class, 'master_mobil_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_mobil_id', 'id');
    }
}
