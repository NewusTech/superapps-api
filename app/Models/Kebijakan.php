<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kebijakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kebijakan';

    protected $fillable = [
        'title', 'deskripsi'
    ];

    public function penginapan()
    {
        return $this->belongsToMany(Penginapan::class, 'kebijakan_penginapan', 'kebijakan_id', 'penginapan_id');
    }
}
