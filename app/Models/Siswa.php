<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 *
 *
 * @property int $id
 * @property string $nisn
 * @property string|null $nis
 * @property string|null $nik
 * @property string $nama
 * @property string $jk
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $alamat
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $desa
 * @property string $kecamatan
 * @property string $kode_pos
 * @property string $kabupaten
 * @property string $Provinsi
 * @property string $hp
 * @property string|null $email
 * @property string|null $foto
 * @property string $agama
 * @property string|null $angkatan
 * @property string $sekolah_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $dapo_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jilid> $jilids
 * @property-read int|null $jilids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiP5> $nilaip5
 * @property-read int|null $nilaip5_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nilai> $nilais
 * @property-read int|null $nilais_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ortu> $ortus
 * @property-read int|null $ortus_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rombel> $rombels
 * @property-read int|null $rombels_count
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 * @method static \Database\Factories\SiswaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereDapoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Siswa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Siswa extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        "dapo_id",
        "nisn",
        "nis",
        "nik",
        "nama",
        "tempat_lahir",
        "tanggal_lahir",
        "jk",
        "alamat",
        "rt",
        "rw",
        "desa",
        "kecamatan",
        "kode_pos",
        "kabupaten",
        "provinsi",
        "hp",
        "email",
        "foto",
        "agama",
        "angkatan",
        "sekolah_id",
        "status",
        "tahun_lulus"
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("siswa")
            ->logOnly(["nisn", "nama", "jk", "sekolah_id"]) // sesuaikan dengan kolommu
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, "sekolah_id", "npsn");
    }

    public function rombels()
    {
        return $this->belongsToMany(Rombel::class, "rombel_siswa");
    }

    public function ortus()
    {
        return $this->hasMany(Ortu::class, "siswa_id", "nisn");
    }

    public function nilaip5()
    {
        return $this->hasMany(NilaiP5::class, "siswa_id", "nisn");
    }

    public function user()
    {
        return $this->morphOne(User::class, "userable");
    }

    public function jilids()
    {
        return $this->belongsToMany(Jilid::class, "jilid_siswa");
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, "siswa_id", "nisn");
    }

    public function kaihs()
    {
        return $this->hasMany(Kaih::class, "siswa_id", "nisn");
    }

    public function ijazah()
    {
        return $this->hasOne(ArsipIjazah::class, "siswa_id", "nisn");
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, "siswa_id", "nisn");
    }

    public function bukuInduk()
    {
        return $this->hasOne(BukuInduk::class, "siswa_id", "nisn");
    }

    public function scopeAktif($request) {
        return $request->whereStatus('aktif');
    }

    public function scopeLulus($request) {
        return $request->whereStatus('lulus');
    }
}
