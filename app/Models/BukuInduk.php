<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuInduk extends Model
{
    use HasFactory;

    protected $fillable = [
        "siswa_id",
        "no_induk",
        "tanggal_masuk",
        "asal_sekolah",
        "tanggal_keluar",
        "alasan_sekolah",
        "sekolah_tujuan",
        "status",
        "keterangan",
    ];

    protected $dates = ["tanggal_masuk", "tanggal_keluar"];

    protected $casts = [
        "tanggal_masuk" => "date",
        "tanggal_keluar" => "date",
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "siswa_id", "nisn");
    }

    public function scopeAktif($query)
    {
        return $query->where("status", "aktif");
    }

    public function scopeLulus($query)
    {
        return $query->where("status", "lulus");
    }

    public function scopeKeluar($query)
    {
        return $query->where("status", "do");
    }

    public function scopePindah($query)
    {
        return $query->where("status", "mutasi");
    }

    public function getTanggalMasukFormattedAttribute()
    {
        return $this->tanggal_masuk
            ? Carbon::parse($this->tanggal_masuk)->format("d/m/Y")
            : null;
    }

    public function getTanggalKeluarFormattedAttribute()
    {
        return $this->tanggal_keluar
            ? Carbon::parse($this->tanggal_keluar)->format("d/m/Y")
            : null;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            "aktif" => "success",
            "lulus" => "primary",
            "do" => "danger",
            "mutasi" => "warning",
            default => "info",
        };
    }
}
