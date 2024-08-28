<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory;

    protected $table = 'image';
    protected $fillable = [
        'image',
    ];

    public function penginapan()
    {
        return $this->belongsToMany(Penginapan::class, 'image_penginapan', 'image_id', 'penginapan_id');
    }
}
