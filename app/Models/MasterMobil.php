<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMobil extends Model
{
    use HasFactory;
    protected $table = 'master_mobil';
    protected $fillable = [
        'nopol',
        'type',
        'jumlah_kursi',
        'status',
        'image_url',
        'fasilitas'
    ];

    public function kursi()
    {
        return $this->hasMany(Kursi::class, 'master_mobil_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_mobil_id', 'id');
    }
}
