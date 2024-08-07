<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kursi extends Model
{
    use HasFactory;

    protected $table = 'kursi';
    protected $fillable = [
        'master_mobil_id',
        'status',
        'nomor_kursi'
    ];


    public function mobil()
    {
        return $this->belongsTo(MasterMobil::class, 'master_mobil_id');
    }
    public static function boot()
    {
        parent::boot();

        self::updated(function ($kursi) {
            if ($kursi->isDirty('status')) {
                $mobil = MasterMobil::find($kursi->master_mobil_id);
                $mobil->available_seats = $kursi->where('status','like', '%kosong%')->count();
                $mobil->save();
            }
        });
    }
}
