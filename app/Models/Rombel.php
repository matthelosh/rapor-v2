<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'pararel',
        'kode',
        'label',
        'fase',
        'tingkat',
        'sekolah_id',
        'guru_id',
        'is_active'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'rombel_siswa');
    }

    public function kktps()
    {
        return $this->hasMany(Kktp::class, 'rombel_id', 'kode');
    }
}
