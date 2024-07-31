<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Rombel;
use Illuminate\Http\Request;
use App\Services\RombelService;
use App\Http\Requests\RombelRequest;

class RombelController extends Controller
{
    public function home(Request $request, RombelService $rombelService)
    {
        try {
            // dd('halo');
            $datas = $rombelService->home($request);
            return Inertia::render(
                'Dash/Rombel',
                $datas
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $sekolahId = $request->query('sekolahId');
            $currentRombel = $request->query('currentRombel');

            $rombels = Rombel::where('sekolah_id', $sekolahId)->whereNot('id', $currentRombel)->get();

            return response()->json(['rombels' => $rombels]);
        } catch(\Exception $e) {
            throw $e;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RombelRequest $request, RombelService $rombelService)
    {
        $rombelService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function attachMember(Request $request, RombelService $rombelService)
    {

        $assign = $rombelService->attachMember($request->query('id'), $request->siswas);
    }
    public function detachMember(Request $request, RombelService $rombelService)
    {
        $assign = $rombelService->detachMember($request->query('id'), $request->siswas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, RombelService $rombelService)
    {
        try {
            $rombelService->store($request);
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rombel $rombel, $id)
    {
        try {
            $rombel = $rombel->findOrFail($id);
            // dd($rombel);
            $rombel->siswas()->detach();
            $delete = $rombel->delete();

            return back()->with("success", true);
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }
}
