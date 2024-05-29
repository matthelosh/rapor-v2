<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
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
}
