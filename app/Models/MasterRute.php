<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class MasterRute extends Model
// {
//     use HasFactory, SoftDeletes;
//     protected $table = 'master_rute';
//     protected $fillable = [
//         'kota_asal',
//         'kota_tujuan',
//         'waktu_keberangkatan',
//         'deskripsi',
//         'image_url',
//         'harga'
//     ];

//     public function jadwal()
//     {
//         return $this->hasMany(Jadwal::class, 'master_rute_id', 'id');
//     }
//     protected static function boot()
//     {
//         parent::boot();

//         static::addGlobalScope('order', function (Builder $builder) {
//             $builder->orderBy('created_at', 'desc');
//         });
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRute extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_rute';

    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'waktu_keberangkatan',
        'deskripsi',
        'image_url',
        'harga'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_rute_id', 'id');
    }

    public function asal()
    {
        return $this->belongsTo(MasterCabang::class, 'kota_asal');
    }

    public function tujuan()
    {
        return $this->belongsTo(MasterCabang::class, 'kota_tujuan');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }
}
