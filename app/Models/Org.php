<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function members()
    {
        return $this->belongsToMany(Guru::class, 'guru_org')->withPivot('jabatan');
    }
}
