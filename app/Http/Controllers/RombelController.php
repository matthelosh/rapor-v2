<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Rombel;
use Illuminate\Http\Request;
use App\Services\RombelService;
use App\Http\Requests\RombelRequest;

class RombelController extends Controller
{
    public function home(Request $request, RombelService $rombelService) {
        $datas = $rombelService->home($request);
        return Inertia::render('Dash/Rombel', 
            $datas
        ); 
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function assignMember(Request $request, RombelService $rombelService)
    {
        $assign = $rombelService->assignMember($request->query('id'), $request->siswas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, RombelService $rombelService)
    {
        try {
            $rombelService->store($request);
        } catch(\Exception $e) 
        {
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
        } catch(\Exception $e)
        {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }
}
