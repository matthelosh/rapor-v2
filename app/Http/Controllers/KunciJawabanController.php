<?php

namespace App\Http\Controllers;

use App\Models\KunciJawaban;
use Illuminate\Http\Request;

class KunciJawabanController extends Controller
{
    public function index()
    {
        try {
            $kuncis = KunciJawaban::with("asesmen")->get();
            return response()->json([
                "message" => "Data Kunci Jawaban",
                "kuncis" => $kuncis,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request, $asesmenId)
    {
        try {
            $kunci = KunciJawaban::updateOrCreate(
                [
                    "asesmen_id" => $asesmenId,
                ],
                [
                    "pg" => $request["pg"] ? json_encode($request["pg"]) : null,
                    "pgk" => $request["pgk"]
                        ? json_encode($request["pgk"])
                        : null,
                    "ps" => $request["ps"] ? json_encode($request["ps"]) : null,
                    "is" => $request["is"] ? json_encode($request["is"]) : null,
                    "ur" => $request["ur"] ? json_encode($request["ur"]) : null,
                ]
            );
            return back()->with("message", "Kunci Jawaban Disimpan.");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
