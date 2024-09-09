<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesAsesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'siswa_id',
        'mulai',
        'selesai',
        'status',
        'skor'
    ];

    public function asesmen()
    {
        return $this->belongsTo(Asesmen::class, 'asesmen_id', 'kode');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function jawabans()
    {
        return $this->hasMany(Jawaban::class, 'proses_id', 'id');
    }
}
