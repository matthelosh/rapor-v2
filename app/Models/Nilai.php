<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $tapel
 * @property string $semester
 * @property string $siswa_id
 * @property string|null $guru_id
 * @property string $rombel_id
 * @property string $mapel_id
 * @property string|null $agama
 * @property string|null $tp_id
 * @property string $tipe
 * @property int $skor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mapel|null $mapel
 * @property-read \App\Models\Rombel|null $rombel
 * @property-read \App\Models\Tp|null $tp
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereSkor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereTpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    public function siswa()
    {
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }

    public function tp()
    {
        return $this->belongsTo(Tp::class, 'tp_id', 'kode');
    }
}
