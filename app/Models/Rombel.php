<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 *
 *
 * @property int $id
 * @property string $tapel
 * @property string $pararel
 * @property string $kode
 * @property string $label
 * @property string $fase
 * @property string $tingkat
 * @property string|null $sekolah_id
 * @property int $guru_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $dapo_id
 * @property-read \App\Models\Guru|null $guru
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kktp> $kktps
 * @property-read int|null $kktps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nilai> $nilais
 * @property-read int|null $nilais_count
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswas
 * @property-read int|null $siswas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereDapoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel wherePararel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rombel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rombel extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        "tapel",
        "dapo_id",
        "pararel",
        "kode",
        "label",
        "fase",
        "tingkat",
        "sekolah_id",
        "guru_id",
        "is_active",
    ];

    // protected $primaryKey = "nip";
    // public $incrementing = false;
    // protected $keyType = "string";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("rombel")
            ->logOnly(["kode", "nama", "tingkat", "tapel", "sekolah_id"]) // sesuaikan dengan kolommu
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function wali_kelas()
    {
        return $this->belongsTo(Guru::class, "guru_id", "id");
    }
    /* public function getWaliAttribute() { */
    /*     return $this->waliKelas->first(); */
    /* } */
    public function gurus()
    {
        return $this->belongsToMany(
            Guru::class,
            "guru_rombel",
            "rombel_id",
            "guru_id",
            "kode",
            "nip",
        )
            ->withPivot("status")
            ->wherePivot("status", "pengajar");
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, "sekolah_id", "npsn");
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, "rombel_siswa");
    }

    public function kktps()
    {
        return $this->hasMany(Kktp::class, "rombel_id", "kode");
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, "rombel_id", "kode");
    }

    public function kaihs()
    {
        return $this->hasMany(Kaih::class, "rombel_id", "kode");
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class, "tapel", "kode");
    }
}
