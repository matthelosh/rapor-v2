<?php

namespace App\Helpers;
use App\Models\Rombel;
use App\Models\Sekolah;

class RombelHelper
{
    public static function data($user, $tapel=null)
    {
        $guruId = auth()->user()->userable->id;
        $sekolah = Sekolah::whereHas('gurus', function($g) use($guruId) {
            $g->where('gurus.id', $guruId);
        })->first();
        if ($user->hasRole('ops'))
        {
            return Rombel::where("tapel", $tapel ??Periode::tapel()->kode)
                ->with([
                    "sekolah" => function($s) {
                        $s->select('id','nama', 'alamat', 'npsn', 'ks_id');
                        $s->with('ks', function($k) {
                            $k->select('id', 'nip','nama', 'gelar_belakang', 'status', 'jk', 'agama', 'pangkat');
                        });
                    },
                    "gurus:id,nip,nama,gelar_belakang,status,jk,agama,jabatan",
                    "wali_kelas:id,nip,nama,gelar_belakang,status,jk,agama",
                    "siswas" => function($q) {
                        $q->select('siswas.id','nama', 'nis', 'nisn', 'jk', 'agama', 'tempat_lahir', 'tanggal_lahir', 'alamat');
                        $q->with("ortus:id,siswa_id,nama,relasi");
                        $q->orderBy('nama', 'ASC');
                    },
                ])
                ->get();
        } else if ($user->hasRole('guru_kelas')) {
            return Rombel::where("guru_id", $user->userable->id)
                ->where("tapel", $tapel ?? Periode::tapel()->kode)
                ->with([
                    "sekolah" => function($s) {
                        $s->select('id','nama', 'alamat', 'npsn', 'ks_id');
                        $s->with('ks', function($k) {
                            $k->select('id', 'nip','nama', 'gelar_belakang', 'status', 'jk', 'agama', 'pangkat');
                        });
                    },
                    "gurus:id,nip,nama,gelar_belakang,status,jk,agama,jabatan",
                    "wali_kelas:id,nip,nama,gelar_belakang,status,jk,agama",
                    "siswas" => function($q) {
                        $q->select('siswas.id','nama', 'nis', 'nisn', 'jk', 'agama', 'tempat_lahir', 'tanggal_lahir', 'alamat');
                        $q->with("ortus:id,siswa_id,nama,relasi");
                        $q->orderBy('nama', 'ASC');
                    },
                ])
                ->first();
        }
    }
}
