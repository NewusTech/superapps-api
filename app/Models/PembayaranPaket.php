<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranPaket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran_paket';
    protected $fillable = [
        'kode_paket',
        'paket_id',
        'metode_id',
        'status',
    ];

    public static function generateUniqueKodePaket()
    {
        do {
            $kode = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_paket', $kode)->exists());

        return $kode;
    }

    public function paket(){
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }

    public function metode(){
        return $this->belongsTo(MetodePembayaran::class, 'metode_id', 'id');
    }
}
