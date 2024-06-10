<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paket';
    protected $fillable = [
        'nama_penerima',
        'nama_pengirim',
        'alamat_pengirim',
        'alamat_penerima',
        'tanggal_dikirim',
        'tanggal_diterima',
        'jenis_paket',
        'biaya',
        'total_berat',
        'status',
    ];
}
