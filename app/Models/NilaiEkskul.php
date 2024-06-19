<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiEkskul extends Model
{
    use HasFactory;

    protected $fillable = [
        'ekskul_id',
        'tapel',
        'semester',
        'siswa_id',
        'nilai',
        'deskripsi'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
