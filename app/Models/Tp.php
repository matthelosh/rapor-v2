<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $mapel_id
 * @property string $kode
 * @property string $teks
 * @property string $elemen
 * @property string $fase
 * @property string $tingkat
 * @property string $semester
 * @property string|null $agama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sekolah_id
 * @property string|null $guru_id
 * @property-read \App\Models\Guru|null $guru
 * @method static \Illuminate\Database\Eloquent\Builder|Tp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereElemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tp extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'kode',
        'teks',
        'elemen',
        'fase',
        'tingkat',
        'semester',
        'agama',
        'guru_id'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
}
