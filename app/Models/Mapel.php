<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $label
 * @property string $fase
 * @property string $kategori
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Elemen> $elemens
 * @property-read int|null $elemens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tp> $tps
 * @property-read int|null $tps_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mapel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'fase',
        'kategori',
        'deskripsi'
    ];

    public function tps()
    {
        return $this->hasMany(Tp::class, 'mapel_id', 'kode');
    }

    public function elemens()
    {
        return $this->hasMany(Elemen::class, 'mapel_id', 'kode');
    }

    public function sekolah()
    {
        return $this->belongsToMany(Sekolah::class, 'mapel_sekolah');
    }
}
