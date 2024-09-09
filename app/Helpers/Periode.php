<?php

namespace App\Helpers;

use App\Models\Semester;
use App\Models\Tapel;

class Periode
{
    public static function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
    public static function semester()
    {
        return Semester::whereIsActive(1)->first();
    }
}
