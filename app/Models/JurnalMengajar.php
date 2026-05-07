<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalMengajar extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tapel',
        'semester',
        'rombel_id',
        'mapel_id',
        'keterangan',
        'materi',
        'tp',
        'elemen',
        'foto_kegiatan',
        'dokumen'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
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
