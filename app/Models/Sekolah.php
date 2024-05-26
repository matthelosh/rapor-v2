<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nama',
        'logo',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'telp',
        'email',
        'website',
        'nama_ks',
        'nip_ks'
    ];

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($logo) => url('/storage/sekolah/' . $logo),
        );
    }
}
