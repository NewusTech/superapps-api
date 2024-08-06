<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penumpang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penumpang';

    protected $fillable = [
        'nama',
        'nik',
        'email',
        'kursi_id',
        'no_telp',
        'pesanan_id'
    ];

    protected static function boot(){
        parent::boot();
        static::created(function ($penumpang) {
            $kursi = Kursi::where('id', $penumpang->kursi_id)->first();
            $kursi->update([
                'status' => 'Terisi'
            ]);
        });
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
