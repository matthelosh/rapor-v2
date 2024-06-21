<?php

namespace App\Http\Controllers;

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
        $mapels = Mapel::with('tps')->get();
        return Inertia::render('Dash/Pembelajaran', [
            'mapels' => $mapels,
            'elemens' => Elemen::all(),
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

    public static function middleware(): array
    {
        return [
            'role:admin|ops|guru_kelas',
        ];
    }
}
