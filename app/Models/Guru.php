<?php

namespace App\Models;

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

    public function user() : MorphOne {
        return $this->morphOne(User::class, 'userable');
    }

    public function sekolahs() {
        return $this->belongsToMany(Sekolah::class, 'guru_sekolah');
    }
}
