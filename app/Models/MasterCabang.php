<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCabang extends Model
{
    use HasFactory;
    protected $table = 'master_cabang';
    protected $fillable = [
        'nama',
        'alamat',
    ];
}
