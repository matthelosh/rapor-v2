<?php

namespace App\Http\Controllers;

use App\Helpers\RombelHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KokurikulerController extends Controller
{
    public function home(Request $request)
    {
        $rombel = RombelHelper::data($request->user());
        return Inertia::render("Dash/Kokurikuler", 
    [
        'rombel' => $rombel,
    ]);
    }
}
