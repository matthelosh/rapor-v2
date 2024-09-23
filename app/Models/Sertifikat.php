<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'workshop_id',
        'guru_id',
        'jp',
        'deskripsi',
        'template',
        'arsip'
    ];



    function penerima()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
