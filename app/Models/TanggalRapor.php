<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalRapor extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'semester',
        'sekolah_id',
        'tipe',
        'tanggal'
    ];

    public function tahun()
    {
        return $this->belongsTo(Tapel::class, 'tapel', 'kode');
    }

    public function sem()
    {
        return $this->belongsTo(Semester::class, 'semester', 'kode');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
}
