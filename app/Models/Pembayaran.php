<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pembayaran';
    protected $fillable = [
        'kode_pembayaran',
        'pesanan_id',
        'amount',
        'expired_at',
        'bukti_url',
        'payment_link',
        'status'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
        static::created(function ($pembayaran) {
            $pembayaran->expired_at = Carbon::parse($pembayaran->created_at)->addMinutes(15);
            $pembayaran->save();
        });
    }

    public static function generateUniqueKodeBayar()
    {
        do {
            $kode = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pembayaran', $kode)->exists());

        return $kode;
    }
}
