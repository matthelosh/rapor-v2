<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Elemen;
use App\Models\Tp;
use Inertia\Inertia;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\PembelajaranTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PembelajaranController extends Controller implements HasMiddleware
{
    use PembelajaranTrait;


    public function home(Request $request)
    {
        return Inertia::render('Dash/Pembelajaran', [
            'mapels' => Mapel::with('tps')->get(),
            'elemens' => Elemen::all(),
            'ekskuls' => Ekskul::all()
        ]);
    }

    public function assignMapel(Request $request)
    {
        // dd($request->sekolahId);
        try {
            $sekolah = Sekolah::findOrFail($request->sekolahId);
            // dd($sekolah);
            $sekolah->mapels()->sync($request->mapels);

            return back()->with('message', "Mapel ditambahkan ke " . $sekolah->nama);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function assignEkskul(Request $request)
    {
        $sekolah = Sekolah::findOrFail($request->sekolahId);
        $sekolah->ekskuls()->sync($request->ekskuls);

        return back()->with('message', "Ekskul ditambahkan ke " . $sekolah->nama);
    }

    public function indexEkskul(Request $request)
    {
        $sekolahId = $request->query('sekolahId');
        $ekskuls =  Ekskul::whereHas('sekolah', function ($q) use ($sekolahId) {
            $q->where(function ($sq) use ($sekolahId) {
                $sq->where('npsn', $sekolahId);
            });
        })->get();

        return response()->json(['ekskuls' => $ekskuls]);
    }


    public static function middleware(): array
    {
        return [
            'role:admin|ops|guru_kelas',
        ];
    }
}
