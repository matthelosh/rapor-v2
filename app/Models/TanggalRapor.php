<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $sekolah_id
 * @property string $tapel
 * @property string $semester
 * @property string $tipe
 * @property string $tanggal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \App\Models\Semester|null $sem
 * @property-read \App\Models\Tapel|null $tahun
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor query()
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TanggalRapor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
