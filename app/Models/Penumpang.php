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
        'no_telp',
        'pesanan_id'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
