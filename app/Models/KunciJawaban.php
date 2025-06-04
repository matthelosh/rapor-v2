<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KunciJawaban extends Model
{
    use HasFactory;
    protected $fillable = [
        'asesmen_id',
        'pg',
        'pgk',
        'ps',
        'bs',
        'is',
        'ur'
    ];

    public function asesmen(): BelongsTo
    {
        return $this->belongsTo(Asesmen::class, 'asesmen_id', 'kode');
    }
}
