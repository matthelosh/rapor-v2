<?php

namespace App\Http\Controllers;

use App\Http\Resources\SekolahResource;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    
    public function store(Request $request, SekolahResource $sekolah) {
        dd($request->all());
    }
}
