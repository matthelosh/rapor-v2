<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'dimensi'
    ];

    public function elemens()
    {
        return $this->hasMany(Elemenp5::class, 'dimensi_id', 'kode');
    }
}
