<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * 
 *
 * @property int $id
 * @property string $nip
 * @property string|null $nuptk
 * @property string|null $gelar_depan
 * @property string $nama
 * @property string|null $gelar_belakang
 * @property string $jk
 * @property string $alamat
 * @property string $hp
 * @property string $status
 * @property string|null $email
 * @property string|null $foto
 * @property string $agama
 * @property string|null $pangkat
 * @property string|null $jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $dapo_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Org> $orgs
 * @property-read int|null $orgs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolahs
 * @property-read int|null $sekolahs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sertifikat> $sertifikats
 * @property-read int|null $sertifikats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tp> $tps
 * @property-read int|null $tps_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Workshop> $workhops
 * @property-read int|null $workhops_count
 * @method static \Database\Factories\GuruFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Guru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guru query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereDapoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereGelarBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereGelarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereNuptk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru wherePangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guru whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'dapo_id',
        'nuptk',
        'gelar_depan',
        'nama',
        'gelar_belakang',
        'jk',
        'alamat',
        'hp',
        'status',
        'email',
        'foto',
        'agama',
        'pangkat',
        'jabatan'
    ];

    // protected function foto(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($foto) => url('/storage/guru/' . $foto),
    //     );
    // }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function sekolahs()
    {
        return $this->belongsToMany(Sekolah::class, 'guru_sekolah');
    }

    public function workhops()
    {
        return $this->belongsToMany(Workshop::class, 'guru_workshop');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'guru_id', 'nip');
    }

    public function tps()
    {
        return $this->hasMany(Tp::class, 'guru_id', 'nip');
    }

    public function orgs()
    {
        return $this->belongsToMany(Org::class, 'guru_org');
    }
}
