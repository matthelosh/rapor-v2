<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'pelaksana',
        'mulai',
        'selesai',
        'deskripsi',
        'is_libur',
        'warna',
        'tapel'
    ];
}
