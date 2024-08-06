<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratKetentuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'syarat_ketentuan';

    protected $fillable = [
        'description'
    ];
}
