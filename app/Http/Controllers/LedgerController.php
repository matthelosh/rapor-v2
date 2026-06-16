<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Mapel;
use App\NilaiTrait;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use App\Helpers\RombelHelper;
use App\Models\Tapel;

class LedgerController extends Controller
{
    use NilaiTrait;

    public function home(Request $request)
    {
        // dd($request->query());

        $sekolah = Sekolah::whereId(
            $request->user()->userable->sekolahs[0]->id
        )->first();
        $npsn = $sekolah->npsn;
        return Inertia::render("Dash/Ledger", [
            'tapels' => Tapel::whereHas('rombels', function($r) use($npsn) {
                $r->where('rombels.sekolah_id', $npsn);
            })->get(),
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
        // $sekolah = Sekolah::whereId(
        //     $request->user()->userable->sekolahs[0]->id
        // )->first();

        $rombels = RombelHelper::data($request->user(), $request->query('tapel') ?? null);
        $rombelWithNilais = $rombels->map(function($rombel) use($request) {
            $rombel->nilais = $this->ledger($request, $rombel);
            return $rombel;
        });

        return response()->json([
            // "mapels" => Mapel::whereHas("sekolah", function ($s) use (
            //     $sekolah
            // ) {
            //     $s->where("sekolahs.id", $sekolah->id);
            // })->get(),
            // "nilais" => $this->ledger($request, $rombels->first()),

            "mapels" => Mapel::select('id','kode','label')->get(),
            "rombels" => $rombelWithNilais,

        ]);
    }
}
