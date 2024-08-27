<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pariwisata extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pariwisata';

    protected $fillable = [
        'judul',
        'slug',
        'lokasi',
        'sub_judul',
        'rating',
        'konten',
        'image_url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($pariwisata) {
            $pariwisata->slug = self::generateSlug($pariwisata->judul);
            $pariwisata->save();
        });
    }
    public static function generateSlug($judul)
    {
        $slug = strtolower($judul);
        $slug = str_replace(' ', '-', $judul);
        return $slug;
    }
}
