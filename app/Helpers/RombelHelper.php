<?php

namespace App\Helpers;
use App\Models\Rombel;

class RombelHelper
{
    public static function data($user)
    {
        // $guruId = Auth::user()->userable->id;
        return Rombel::where("guru_id", $user->userable->id)
            ->with("sekolah.ks")
            ->with("wali_kelas")
            ->with("siswas.ortus")
            ->get();
    }
}
