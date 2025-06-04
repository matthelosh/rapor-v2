<?php

namespace App\Http\Controllers;

use App\Models\KunciJawaban;
use Illuminate\Http\Request;

class KunciJawabanController extends Controller
{
    public function index()
    {
        try {
            $kuncis = KunciJawaban::with('asesmen')->get();
            return response()->json([
                'message' => 'Data Kunci Jawaban',
                'kuncis' => $kuncis
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function store(Request $request, $asesmenId)
    {
        try {
            dd($request->all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
