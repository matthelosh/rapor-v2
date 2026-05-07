<?php

namespace App\Http\Controllers;

use App\Models\Gugus;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GugusController extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/Gugus/Home',
                [
                    'guguses' => Gugus::with('sekolahs.gurus', 'sekretariat.ks')->get(),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            Gugus::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                ],
                [
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi,
                    'sekretariat' => $request->sekretariat ?? null,
                ]
            );
            return back()->with('message', 'Gugus Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Gugus $gugus, $id)
    {
        try {
            Sekolah::where('gugus_id', $id)->update(['gugus_id' => null]);
            $gugus::findOrFail($id)->delete();
            return back()->with('message', 'Gugus dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
