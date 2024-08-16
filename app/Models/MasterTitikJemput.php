<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTitikJemput extends Model
{
    use HasFactory;
    protected $table = 'master_titik_jemput';
    protected $fillable = ['nama', 'master_cabang_id'];

    public function master_cabang()
    {
        return $this->belongsTo(MasterCabang::class, 'master_cabang_id');
    }
}
