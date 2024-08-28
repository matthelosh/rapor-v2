<?php

namespace App\Http\Controllers;

use App\Models\Tp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\TpRequest;

class TpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tps  = Tp::where([
                ['mapel_id', '=', $request->mapelId],
                ['tingkat', '=', $request->tingkat],
                ['semester', '=', $request->semester],
                ['agama', '=', $request->agama],
            ])->get();
            return response()->json(['tps' => $tps]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function impor(Request $request)
    {
        try {
            foreach ($request->tps as $tp) {
                Tp::updateOrCreate(
                    [
                        'kode' => $tp['kode'],
                    ],
                    [
                        'mapel_id' => $tp['mapel_id'],
                        'teks' => $tp['teks'],
                        'elemen' => $tp['elemen'],
                        'fase' => $tp['fase'],
                        'tingkat' => $tp['tingkat'],
                        'semester' => $tp['semester'],
                        'agama' => $tp['agama'] ?? null,
                    ]
                );
            }
            return back()->with("message", "Elemen diimpor");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(TpRequest $request)
    {
        try {
            Tp::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                ],
                [
                    'mapel_id' => $request['mapel_id'],
                    'kode' => $request['kode'] ?? strtolower(Str::random(8)),
                    'teks' => $request['teks'],
                    'elemen' => $request['elemen'],
                    'fase' => $request['fase'] ?? (in_array($request['tingkat'], ['1', '2']) ? 'A' : (in_array($request['tingkat'], ['3', '4']) ? 'B' : 'C')),
                    'tingkat' => $request['tingkat'],
                    'semester' => $request['semester'],
                    'agama' => $request['agama'] ?? null,
                ]
            );
            return back()->with('message', "TP Disimpan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tp $tp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tp $tp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tp $tp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tp $tp, $id)
    {
        try {
            $tp->destroy($id);

            return back()->with('message', 'Tp Dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
