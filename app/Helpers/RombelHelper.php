<?php

namespace App\Helpers;
use App\Models\Rombel;

class RombelHelper
{
    public static function data($user)
    {
        // $guruId = Auth::user()->userable->id;
        return Rombel::where("guru_id", $user->userable->id)
            ->where("tapel", Periode::tapel()->kode)
            ->with([
                "sekolah:id,npsn,nama",
                "sekolah.ks:id,nip,nama,gelar_belakang,status",
                "gurus:id,nip,nama,gelar_belakang,status,jk,agama,jabatan",
                "wali_kelas:id,nip,nama,gelar_belakang,status,jk,agama",
                "siswas" => function($q) {
                    $q->select('siswas.id','nama', 'nis', 'nisn', 'jk', 'agama');
                    $q->with("ortus:id,siswa_id,nama,relasi");
                    $q->orderBy('nama', 'ASC');
                },
            ])
            ->get();
    }
}
