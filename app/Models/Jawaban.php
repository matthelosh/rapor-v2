<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'siswa_id',
        'soal_id',
        'teks',
        'is_benar',
        'proses_id'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->setTimezone("Asia/Jakarta");
    }

    public function proses()
    {
        return $this->belongsTo(ProsesAsesmen::class, 'proses_id', 'id');
    }

    public function asesmen()
    {
        return $this->belongsTo(Asesmen::class, 'asesmen_id', 'kode');
    }
}
