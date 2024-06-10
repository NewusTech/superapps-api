<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSupir extends Model
{
    use HasFactory;
    protected $table = 'master_supir';
    protected $fillable = ['nama', 'no_telp'];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'master_supir_id', 'id');
    }
}
