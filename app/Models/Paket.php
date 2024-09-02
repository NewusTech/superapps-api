<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paket';
    protected $fillable = [
        'resi',
        'nama_penerima',
        'nama_pengirim',
        'alamat_pengirim',
        'alamat_penerima',
        'tanggal_dikirim',
        'tanggal_diterima',
        'tujuan',
        'catatan',
        'jumlah_barang',
        'no_telp_penerima',
        'no_telp_pengirim',
        'jenis_paket',
        'biaya',
        'total_berat',
        'status',
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function($model){
            $model->resi = self::generateUniqueResi();
            $model->status = 'Menunggu Pembayaran';
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    public static function generateUniqueResi()
    {
        do {
            $resi = now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('resi', $resi)->exists());
        return $resi;
    }

    public function pembayaran()
    {
        return $this->hasOne(PembayaranPaket::class, 'paket_id', 'id');
    }
}
