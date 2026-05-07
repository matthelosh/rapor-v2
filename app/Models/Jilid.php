<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $sekolah_id
 * @property string $nama
 * @property string $juz
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JurnalSpn> $jurnals
 * @property-read int|null $jurnals_count
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswas
 * @property-read int|null $siswas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereJuz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jilid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Jilid extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'nama',
        'juz'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'jilid_siswa');
    }

    public function jurnals()
    {
        return $this->hasMany(JurnalSpn::class);
    }
}
