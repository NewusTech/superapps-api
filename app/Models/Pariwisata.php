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
        'sub-judul',
        'rating',
        'konten',
        'image_url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($pariwisata) {
            $pariwisata->slug = Pariwisata::generateSlug($pariwisata->judul);
        });
    }
    public function generateSlug($judul)
    {
        $slug = preg_replace('/[^a-zA-Z0-9]/', '-', $judul);
        $slug = strtolower($slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
