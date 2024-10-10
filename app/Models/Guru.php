<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
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
