<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'semester',
        'siswa_id',
        'guru_id',
        'rombel_id',
        'mapel_id',
        'agama',
        'tp_id',
        'tipe',
        'skor'
    ];

    public function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id','kode');
    }

    public function siswa() {
        
    }
}
