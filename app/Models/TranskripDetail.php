<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranskripDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "transkrip_id",
        "mapel_id",
        "nilai_rapor",
        "nilai_psaj",
        "nilai_akhir",
    ];

    public function transkrip(): BelongsTo
    {
        return $this->belongsTo(Transkrip::class, "transkrip_id", "kode");
    }
}
