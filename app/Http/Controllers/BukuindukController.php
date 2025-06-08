<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BukuindukController extends Controller
{
    public function home(Request $request)
    {
        return Inertia::render("Dash/BukuInduk");
    }
}
