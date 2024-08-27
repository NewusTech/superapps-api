<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasilitas';
    protected $fillable = [
        'nama'
    ];

    public function penginapan()
    {
        return $this->belongsToMany(Penginapan::class, 'fasilitas_penginapan', 'fasilitas_id', 'penginapan_id');
    }
}
