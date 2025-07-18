<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

class ArsipIjazah extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_seri',
        'tapel',
        'siswa_id',
        'sekolah_id',
        'url',
        'url_transkrip',
        'url_skl',
        'keterangan',
    ];

    protected $appends = [
        'url_public',
        'url_transkrip_public',
        'url_skl_public',
    ];

    // URL publik untuk file utama
    public function getUrlPublicAttribute()
    {
        return $this->url ? Storage::disk('s3')->url($this->url) : null;
    }

    // URL publik untuk transkrip
    public function getUrlTranskripPublicAttribute()
    {
        return $this->url_transkrip ? Storage::disk('s3')->url($this->url_transkrip) : null;
    }

    // URL publik untuk SKL
    public function getUrlSklPublicAttribute()
    {
        return $this->url_skl ? Storage::disk('s3')->url($this->url_skl) : null;
    }
    public function siswa(): BelongsTo {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
    public function sekolah(): BelongsTo {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
}
