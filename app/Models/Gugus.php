<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gugus extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'sekretariat'
    ];

    public function sekolahs()
    {
        return $this->hasMany(Sekolah::class, 'gugus_id', 'id');
    }

    public function sekretariat()
    {
        return $this->belongsTo(Sekolah::class, 'sekretariat', 'npsn');
    }
}
