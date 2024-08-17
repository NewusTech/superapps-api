<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kursi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kursi';
    protected $fillable = [
        'master_mobil_id',
        'jadwal_id',
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
                $kursi->jadwal()->update([
                    'available_seats' => $kursi->jadwal->kursi->where('status', 'like', '%kosong%')->count()
                ]);
            }
        });
    }
    public function jadwal (){
        return $this->belongsTo(Jadwal::class, 'jadwal_id','id');
    }
}
