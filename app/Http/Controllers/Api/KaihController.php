<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kaih;
use Illuminate\Http\Request;
use Throwable;

class KaihController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->user());
        // return response()->json([
        //     "user" => $request->user()->userable,
        // ]);
        try {
            $detail = $request->user()->userable;

            $datas = Kaih::where("siswa_id", $detail->nisn)->get();
            return response()->json([
                "success" => true,
                "datas" => $datas,
            ]);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error: " . $th,
                ],
                $th->getCode()
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $store = Kaih::create([
                "rombel_id" => $request->query("rombelId"),
                "siswa_id" => $request->query("siswaId"),
                "semester" => $request->query("semester"),
                "kebiasaan" => $request->kebiasaan,
                "is_done" => $request->query("is_done"),
                "waktu" => now(),
                "keterangan" => $request->keterangan,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Input disimpan",
            ]);
        } catch (Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error: " . $th->getMessage(),
                ],
                500
            );
        }
    }
}
