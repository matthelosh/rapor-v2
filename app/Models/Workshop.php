<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;
    protected $fillable = [
        'agenda_id',
        'nama',
        'lokasi',
        'deskripsi',
        'pelaksana',
        'nara_sumber',
        'mulai',
        'selesai'
    ];

    public function pesertas()
    {
        return $this->belongsToMany(Guru::class, 'guru_workshop');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }
}
