<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property string $npsn
 * @property string $nama
 * @property string|null $logo
 * @property string $alamat
 * @property string $desa
 * @property string $kecamatan
 * @property string $kabupaten
 * @property string $kode_pos
 * @property string|null $telp
 * @property string|null $email
 * @property string|null $website
 * @property int|null $ks_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $gugus_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ekskul> $ekskuls
 * @property-read int|null $ekskuls_count
 * @property-read \App\Models\Gugus|null $gugus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guru> $gurus
 * @property-read int|null $gurus_count
 * @property-read \App\Models\Guru|null $ks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mapel> $mapels
 * @property-read int|null $mapels_count
 * @property-read \App\Models\Guru|null $ops
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rombel> $rombels
 * @property-read int|null $rombels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswas
 * @property-read int|null $siswas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereGugusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereKsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereNpsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah whereWebsite($value)
 * @mixin \Eloquent
 */
class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nama',
        'logo',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'telp',
        'email',
        'website',
        'ks_id',
        'gugus_id'
    ];


    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($logo) => url('/storage/sekolah/' . $logo),
        );
    }

    function ops()
    {
        return $this->belongsTo(Guru::class, 'npsn', 'nip');
    }

    function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_sekolah');
    }

    function ks(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'ks_id', 'id');
    }


    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'sekolah_id', 'npsn');
    }

    function rombels()
    {
        return $this->hasMany(Rombel::class, 'sekolah_id', 'npsn');
    }

    function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'mapel_sekolah');
    }
    function ekskuls()
    {
        return $this->belongsToMany(Ekskul::class, 'ekskul_sekolah');
    }

    function gugus()
    {
        return $this->belongsTo(Gugus::class, 'gugus_id', 'id');
    }

    function ijazahs()
    {
        return $this->hasMany(ArsipIjazah::class, 'sekolah_id', 'npsn');
    }
}
