<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kktp extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'semester',
        'sekolah_id',
        'mapel_id',
        'rombel_id',
        'tingkat',
        'minimal',
        'deskripsi'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
