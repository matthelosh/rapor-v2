<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $guru_id
 * @property string $tingkat
 * @property string $semester
 * @property string $mapel_id
 * @property string|null $agama
 * @property int $tp_id
 * @property string $pertanyaan
 * @property string|null $a
 * @property string|null $b
 * @property string|null $c
 * @property string|null $d
 * @property string|null $kunci
 * @property string $tipe
 * @property string $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asesmen> $asesmen
 * @property-read int|null $asesmen_count
 * @property-read \App\Models\Guru|null $guru
 * @method static \Illuminate\Database\Eloquent\Builder|Soal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Soal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Soal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereKunci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal wherePertanyaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereTpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Soal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tingkat',
        'semester',
        'mapel_id',
        'agama',
        'tp_id',
        'pertanyaan',
        'a',
        'b',
        'c',
        'd',
        'kunci',
        'tipe',
        'level'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    public function asesmen()
    {
        return $this->belongsToMany(Asesmen::class, 'asesmen_soal');
    }
}
