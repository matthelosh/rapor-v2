<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'sekolah_id',
        'pembina',
        'nip_pembina',
        'keterangan',
        'sifat',
        'is_active'
    ];
}
