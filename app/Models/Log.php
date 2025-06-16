<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "keterangan", "ip_address"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "name");
    }
}
