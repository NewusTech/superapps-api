<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
        'user_id',
        'metode_id',
        'biaya_tambahan',
        'nama',
        'email',
        'nik',
        'expired_at',
        'no_telp',
        'master_titik_jemput_id',
        'titik_antar_id',
        'status'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function metode()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_id', 'id');
    }
    public function pembayaran(){
        return $this->hasOne(Pembayaran::class, 'pesanan_id', 'id');
    }
    public function penumpang()
    {
        return $this->hasMany(Penumpang::class, 'pesanan_id', 'id');
    }

    public function titikJemput()
    {
        return $this->belongsTo(MasterTitikJemput::class, 'master_titik_jemput_id', 'id');
    }

    public function titikAntar(){
        return $this->belongsTo(MasterTitikJemput::class, 'titik_antar_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }




    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        static::creating(function ($pesanan) {
            $pesanan->kode_pesanan = self::generateUniqueKodePesanan();
        });
        static::created(function ($pesanan) {
            $pesanan->expired_at = Carbon::parse($pesanan->created_at)->addMinutes(15);
            $pesanan->save();
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
