<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RaporController extends Controller
{
    public function home(Request $request) {
        
        return Inertia::render('Dash/Rapor', [

        ]);
    }

    public function periodik(Request $request) {
        
        return Inertia::render('Dash/Periodik', [

        ]);
    }

}
