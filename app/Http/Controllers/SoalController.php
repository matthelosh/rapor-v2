<?php

namespace App\Http\Controllers;

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
            if ($request->user()->hasRole('admin')) {
                $soals = Soal::all();
            } else {
                $soals = Soal::whereGuruId($request->user()->userable->nip)->get();
            }


            return Inertia::render(
                'Dash/Soal/Home',
                [
                    'canAddSoal' => $user->can('add_soal'),
                    'soals' => $soals,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function allSoal(Request $request)
    {
        try {
            $asesmen_id = $request->asesmen_id;
            // dd($asesmen_id);
            $soals = Soal::whereDoesntHave('asesmen', function ($a) use ($asesmen_id) {
                $a->where('asesmen_id', $asesmen_id);
            })
                ->where([
                    ['tingkat', '=', $request->tingkat],
                    ['mapel_id', '=', $request->mapel_id],
                    ['agama', '=', $request->agama],

                ])->get();
            return response()->json([
                'soals' => $soals
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $image = $request->image;
            $store = Storage::putFileAs('public/soal', $image, Str::random(8) . '.' . $image->extension());
            return response()->json(
                [
                    'url' => Storage::url($store)
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            Soal::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                ],
                [
                    'guru_id' => $request->user()->hasRole('admin') ? $request->user()->id : $request->user()->userable->nip,
                    'tp_id' => $request->tp_id,
                    'tingkat' => $request->tingkat,
                    'semester' => $request->semester,
                    'mapel_id' => $request->mapel_id,
                    'agama' => $request->mapel_id == 'pabp' ? $request->agama : null,
                    'pertanyaan' => $request->pertanyaan,
                    'a' => $request->a,
                    'b' => $request->b,
                    'c' => $request->c,
                    'd' => $request->d ?? null,
                    'kunci' => $request->kunci,
                    'tipe' => $request->tipe,
                    'level' => $request->level
                ]
            );

            return back()->with('message', 'Soal Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Soal $soal, $id)
    {
        try {
            DB::table('asesmen_soal')->where('soal_id', $id)->delete();
            $soal::find($id)->delete();

            return back()->with('message', 'Soal dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
