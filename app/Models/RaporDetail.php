<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaporDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "rapor_id",
        "mapel_id",
        "uh",
        "ts",
        "as",
        "rerata",
    ];

    public function rapor()
    {
        return $this->belongsTo(Rapor::class, "rapor_id", "kode");
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, "mapel_id", "kode");
    }
}