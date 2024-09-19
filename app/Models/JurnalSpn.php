<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalSpn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'jilid_id',
        'guru_id',
        'materi',
        'fotos',
        'absensis',
        'keterangan'
    ];
}
