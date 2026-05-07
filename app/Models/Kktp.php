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
 * @property string $sekolah_id
 * @property string|null $rombel_id
 * @property string $mapel_id
 * @property string $tingkat
 * @property int $minimal
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mapel|null $mapel
 * @property-read \App\Models\Rombel|null $rombel
 * @property-read \App\Models\Sekolah|null $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereMinimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kktp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
