<?php

namespace App\Http\Controllers;

use App\Models\Asesmen;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SoalController extends Controller
{
    public function home(Request $request)
    {
        try {
            $user = $request->user();
            if ($request->user()->hasRole("admin")) {
                $soals = Soal::all();
            } else {
                $soals = Soal::whereGuruId(
                    $request->user()->userable->nip
                )->get();
            }

            return Inertia::render("Dash/Soal/Home", [
                "canAddSoal" => $user->can("add_soal"),
                "soals" => $soals,
                "sekolahs" => \sekolahs($user),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function allSoal(Request $request)
    {
        try {
            $asesmen_id = $request->asesmen_id;
            $asesmen = Asesmen::whereId($asesmen_id)->first();
            // dd($asesmen);
            $agama = $request->agama
                ? $request->agama
                : ($asesmen->mapel_id == "pabp"
                    ? $request->user()->userable->agama
                    : "%");
            // dd($agama);
            $soals = Soal::whereDoesntHave("asesmen", function ($a) use (
                $asesmen_id
            ) {
                $a->where("asesmen_id", $asesmen_id);
            })
                ->where([
                    ["tingkat", "=", $request->tingkat],
                    ["semester", "=", $asesmen->semester],
                    ["mapel_id", "=", $request->mapel_id],
                    ["agama", "LIKE", $agama ?? "%"],
                ])
                ->get();
            return response()->json([
                "soals" => $soals,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $image = $request->image;
            $store = Storage::putFileAs(
                "public/soal",
                $image,
                Str::random(8) . "." . $image->extension(),
                'public'
            );
            return response()->json([
                "url" => Storage::url($store),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            Soal::updateOrCreate(
                [
                    "id" => $request->id ?? null,
                ],
                [
                    "guru_id" => $request->user()->hasRole("admin")
                        ? $request->user()->id
                        : $request->user()->userable->nip,
                    "tp_id" => $request->tp_id,
                    "tingkat" => $request->tingkat,
                    "semester" => $request->semester,
                    "mapel_id" => $request->mapel_id,
                    "agama" =>
                        $request->mapel_id == "pabp" ? $request->agama : null,
                    "pertanyaan" => $request->pertanyaan,
                    "jawabans" => implode("|", [
                        $request->a,
                        $request->b,
                        $request->c,
                        $request->d ?? null,
                    ]),
                    "kunci" => $request->tipe == 'pilihan_ganda_kompleks' ? implode("|", $request->kunci) : $request->kunci,
                    "tipe" => $request->tipe,
                    "level" => $request->level,
                ]
            );

            return back()->with("message", "Soal Disimpan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Soal $soal, $id)
    {
        try {
            DB::table("asesmen_soal")->where("soal_id", $id)->delete();
            $soal::find($id)->delete();

            return back()->with("message", "Soal dihapus");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
