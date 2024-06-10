<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pembayaran';
    // protected $guarded = ['id'];
    protected $fillable = [
        'kode_pembayaran',
        'pesanan_id',
        'status'
    ];

    public static function generateUniqueKodeBayar()
    {
        do {
            $kode = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pembayaran', $kode)->exists());

        return $kode;
    }
}
