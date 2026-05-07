<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Models\Asesmen;
use App\Models\Analisis;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalisisController extends Controller
{
    public function home(Request $request)
    {
        try {
            if (
                $request
                    ->user()
                    ->hasRole(["guru_agama", "guru_pjok", "guru_inggris"])
            ) {
                $role = $request->user()->getRoleNames()[0];
                $mapels = [
                    "guru_agama" => "pabp",
                    "guru_pjok" => "pjok",
                    "guru_inggris" => "bing",
                ];
                $mapel = $mapels[$role];
                $agama =
                    $mapel != "pabp" ? "%" : $request->user()->userable->agama;
                $asesmens = Asesmen::where("mapel_id", $mapel)
                    ->where("agama", $agama)
                    ->with("mapel", "analises", "kunci")
                    ->get();
            } else {
                $asesmens = Asesmen::where("tapel", Periode::tapel()->kode)
                    ->with("mapel")
                    ->with("analises")
                    ->with("kunci")
                    ->get();
            }
            return Inertia::render("Dash/Analisis/Home", [
                "asesmens" => $asesmens,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        /* dd($request->all()); */
        $store = Analisis::updateOrCreate(
            [
                "asesmen_id" => $request->asesmen_id,
            ],
            [
                "kunci" => null,
                "hasil" => json_encode($request->hasil),
                "keterangan" => "Terang",
            ]
        );

        return back()->with("message", "Analisis disimpan");
    }

    public function cekJawaban(Request $request)
    {
        $jawaban = $request->input("jawaban");
        $kunci = $request->input("kunci");

        $prompt = "
        Koreksi jawaban siswa \"${jawaban}\" berdasarkan kunci \"${kunci}\". Kebenaran jawaban berdasrakan substansinya.
        Berikan penilaian benar/salah dan alasannya secara singkat. Berikan response dalam format JSON dengan kunci 'status' (String Benar/Salah), 'alasan' (String)


        ";

        $apiKey = env("GEMINI_API_KEY");
        try {
            $response = \Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}",
                [
                    "contents" => [
                        [
                            "parts" => [["text" => $prompt]],
                        ],
                    ],
                ]
            );

            // Cek apakah ada error dari API Gemini
            if ($response->failed()) {
                return response()->json(
                    [
                        "error" => "Gemini API call failed",
                        "details" => $response->json(),
                    ],
                    $response->status()
                );
            }

            $responseData = $response->json();

            // Ekstrak teks dari respons Gemini
            $geminiText =
                $responseData["candidates"][0]["content"]["parts"][0]["text"] ??
                "No response text from Gemini.";

            return response()->json([
                "status" => "success",
                "correction" => $geminiText,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "error" => "An error occurred",
                    "details" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
