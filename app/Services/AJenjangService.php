<?php

namespace App\Services;

use App\Helpers\Periode;
use App\Models\Rombel;
use App\Models\Guru;

class AJenjangService
{
    public function __construct() {}

    public function home()
    {
        $guru = Guru::where("nip", auth()->user()->userable->nip)
            ->with("rombels", function ($q) {
                $q->where("tapel", Periode::tapel()->kode)->with(
                    "siswas",
                    "sekolah"
                );
            })
            ->first();
        $rombels = $guru->rombels;

        return ["rombels" => $rombels];
    }
}
