<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'fase',
        'kategori',
        'deskripsi'
    ];

    public function tps() {
        return $this->hasMany(Tp::class, 'mapel_id','kode');
    }

    public function elemens() {
        return $this->hasMany(Elemen::class, 'mapel_id','kode');
    }
}
