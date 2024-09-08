<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tingkat',
        'semester',
        'mapel_id',
        'agama',
        'tp_id',
        'pertanyaan',
        'a',
        'b',
        'c',
        'd',
        'kunci',
        'tipe',
        'level'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    public function asesmen()
    {
        return $this->belongsToMany(Asesmen::class, 'asesmen_soal');
    }
}
