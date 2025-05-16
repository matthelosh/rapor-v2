<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transkrip;
use App\Models\Siswa;

class VerifyController extends Controller
{
    public function verifyTranskrip(Request $request, $nisn)
    {
        try {
            // $transkrip = Transkrip::where("siswa_id", $nisn)
            //     ->with("siswa.sekolah")
            //     ->first();
            $data = json_decode(
                json_encode([
                    "rombel" => "Tes",
                    "siswa" => Siswa::where("nisn", $nisn)
                        ->with("sekolah")
                        ->first(),
                ])
            );
            return view("verify.transkrip", [
                "is_verified" => $data ?? false,
                "transkrip" => $data ?? [],
                "message" => $data
                    ? "Selamat transkrip nilai Anda valid dan terverifikasi oleh sistem PKG Kecamatan Wagir"
                    : "Maaf! Transkrip Anda tidak ditemukan di sistem PKG Kecamatan Wagir",
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
