<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Mapel;
use App\NilaiTrait;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    use NilaiTrait;

    public function home(Request $request)
    {
        // dd($request->query());

        $sekolah = Sekolah::whereId(
            $request->user()->userable->sekolahs[0]->id
        )->first();
        return Inertia::render("Dash/Ledger", [
            // "mapels" => Mapel::whereHas("sekolah", function ($s) use (
            //     $sekolah
            // ) {
            //     $s->where("sekolahs.id", $sekolah->id);
            // })->get(),
            // "nilais" => $this->ledger($request),
        ]);
    }

    public function index(Request $request)
    {
        $sekolah = Sekolah::whereId(
            $request->user()->userable->sekolahs[0]->id
        )->first();
        return response()->json([
            "mapels" => Mapel::whereHas("sekolah", function ($s) use (
                $sekolah
            ) {
                $s->where("sekolahs.id", $sekolah->id);
            })->get(),
            "nilais" => $this->ledger($request),
        ]);
    }
}
