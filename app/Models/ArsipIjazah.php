<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipIjazah extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_seri',
        'tapel',
        'siswa_id',
        'sekolah_id',
        'url',
        'keterangan',
    ];

    public function siswa(): BelongsTo {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
    public function sekolah(): BelongsTo {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
}
