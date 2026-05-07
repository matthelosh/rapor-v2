<?php

namespace App\Helpers;

use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Support\Facades\Cache;

class Periode
{
    public static function tapel()
{
    // return Cache::remember('current_tapel', 3600, function () {
        return Tapel::whereIsActive(1)->first();
    // });
}

public static function semester()
{
    // return Cache::remember('current_semester', 3600, function () {
        return Semester::whereIsActive(1)->first();
    // });
}
}
