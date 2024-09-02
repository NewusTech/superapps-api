<?php

namespace App\Models;

use App\Jobs\Rental\CancelRentalOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rental';
    protected $fillable = [
        'kode_pesanan',
        'durasi_sewa',
        'area',
        'tanggal_mulai_sewa',
        'tanggal_akhir_sewa',
        'alamat_keberangkatan',
        'jam_keberangkatan',
        'nama',
        'email',
        'expired_at',
        'mobil_rental_id',
        'nik',
        'username_fb',
        'username_ig',
        'image_ktp',
        'image_swafoto',
        'catatan_sopir',
        'no_telp',
        'alamat',
        'metode_id',
        'user_id',
        'all_in',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
        static::created(function ($rental) {
            $rental->expired_at = Carbon::parse($rental->created_at)->addMinutes(15);
            $rental->kode_pesanan = self::generateUniqueKodePesanan();
            $rental->save();

            $delay = Carbon::now()->diffInSeconds(Carbon::parse($rental->expired_at), false);
            if ($delay > 0) {
                CancelRentalOrder::dispatch($rental)->delay(now()->addSeconds($delay));
            } else {
                CancelRentalOrder::dispatch($rental);
            }
        });
    }

    public function mobil(){
        return $this->belongsTo(MobilRental::class, 'mobil_rental_id', 'id');
    }

    public function pembayaran(){
        return $this->belongsTo(PembayaranRental::class, 'id', 'rental_id');
    }

    public function metode(){
        return $this->belongsTo(MetodePembayaran::class, 'metode_id', 'id');
    }
    private static function generateUniqueKodePesanan()
    {
        do {
            $kode = now()->format('YmdHis') . '-' . rand(1000, 9999);
        } while (self::where('kode_pesanan', $kode)->exists());

        return $kode;
    }
}
