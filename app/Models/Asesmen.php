<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $deskripsi
 * @property string $mapel_id
 * @property string $tanggal
 * @property string $mulai
 * @property string $selesai
 * @property string $tingkat
 * @property string $jenis
 * @property string|null $rombel_id
 * @property string $sekolah_id
 * @property \App\Models\Semester|null $semester
 * @property \App\Models\Tapel|null $tapel
 * @property string $guru_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $kelas
 * @property string|null $agama
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Analisis> $analises
 * @property-read int|null $analises_count
 * @property-read \App\Models\Guru|null $guru
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jawaban> $jawabans
 * @property-read int|null $jawabans_count
 * @property-read \App\Models\Mapel|null $mapel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $pesertas
 * @property-read int|null $pesertas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProsesAsesmen> $proses
 * @property-read int|null $proses_count
 * @property-read \App\Models\ProsesAsesmen|null $proses_siswa
 * @property-read \App\Models\Rombel|null $rombel
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Soal> $soals
 * @property-read int|null $soals_count
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asesmen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Asesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', //npsn-rombel-mapel-sem-jenis-unique
        'nama',
        'deskripsi',
        'mapel_id',
        'tanggal',
        'jenjang',
        'mulai',
        'selesai',
        'agama',
        'kelas',
        'tingkat',
        'jenis',
        'rombel_id',
        'sekolah_id',
        'semester',
        'tapel',
        'guru_id'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
    public function rombel()
    {
        return $this->belongsTo(ROmbel::class, 'rombel_id', 'kode');
    }

    public function  soals()
    {
        return $this->belongsToMany(Soal::class, 'asesmen_soal');
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class, 'tapel', 'kode');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester', 'kode');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }

    public function proses()
    {
        return $this->hasMany(ProsesAsesmen::class, 'asesmen_id', 'kode');
    }
    public function proses_siswa()
    {
        return $this->hasOne(ProsesAsesmen::class, 'asesmen_id', 'kode')->latest();
    }

    public function jawabans()
    {
        return $this->hasMany(Jawaban::class, 'proses_id', 'id');
    }

    public function pesertas()
    {
        return $this->belongsToMany(Siswa::class, 'asesmen_siswa');
    }

    public function analises()
    {
        return $this->hasMany(Analisis::class, 'asesmen_id', 'kode');
    }
    public function kunci(): HasOne
    {
        return $this->hasOne(KunciJawaban::class, 'asesmen_id', 'kode');
    }
}
