<?php

namespace App\Models;

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
        'image_url'
    ];

    public function kursi()
    {
        return $this->hasMany(Kursi::class, 'master_mobil_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_mobil_id', 'id');
    }
}
