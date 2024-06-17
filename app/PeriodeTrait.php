<?php

namespace App;

use App\Models\Semester;

trait PeriodeTrait
{
    public function periode() {
        return [
            'semester' => Semester::whereIsActive(1)->first(),
            'tapel' => Tapel::whereisActive(1)->first()
        ];
    }
}
