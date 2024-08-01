<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pesanan';
    protected $fillable = [
        'kode_pesanan',
        'jadwal_id',
        'kursi_id',
        'user_id',
        'metode_id',
        'biaya_tambahan',
        'master_titik_jemput_id',
        'status'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function penumpang()
    {
        return $this->hasMany(Penumpang::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesanan) {
            $pesanan->kode_pesanan = self::generateUniqueKodePesanan();
        });
    }

    private static function generateUniqueKodePesanan()
    {
        do {
            $kode = 'TR-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pesanan', $kode)->exists());

        return $kode;
    }
}
