<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'kode',
        'teks',
        'elemen',
        'fase',
        'tingkat',
        'semester',
        'agama',
        'guru_id'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
}
