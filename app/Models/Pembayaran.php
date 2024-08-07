<?php

namespace App\Models;

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
        'status'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }

    public static function generateUniqueKodeBayar()
    {
        do {
            $kode = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pembayaran', $kode)->exists());

        return $kode;
    }
}
