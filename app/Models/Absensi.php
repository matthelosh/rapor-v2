<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'siswa_id',
        'ijin',
        'sakit',
        'alpa'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
