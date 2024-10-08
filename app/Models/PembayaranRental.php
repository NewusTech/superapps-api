<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranRental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran_rental';

    protected $fillable = [
        'rental_id',
        'nominal',
        'status',
        'expired_at',
        'bukti_url',
        'kode_pembayaran',
        'keterangan',
        'payment_link',
    ];

    public static function generateUniqueKodeBayar()
    {
        do {
            $kode = 'INV-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pembayaran', $kode)->exists());

        return $kode;
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'id');
    }
}
