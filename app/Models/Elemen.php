<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'fase',
        'nama',
        'agama',
    ];

    public function cp()
    {
        return $this->belongsTo(Cp::class);
    }
}
