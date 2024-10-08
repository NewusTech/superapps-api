<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodePembayaran extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'metode_pembayaran';
    protected $fillable = [
        'metode',
        'keterangan',
        'no_rek',
        'bank',
        'kode',
        'biaya_tambahan',
        'img'
    ];
}
