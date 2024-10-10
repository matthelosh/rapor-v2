<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Org;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrgController extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/Org/Home',
                [
                    'orgs' => Org::with('members')->get()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nonMember(Request $request)
    {
        try {
            $orgId = $request->orgId;
            $nonmembers = Guru::whereDoesntHave(
                'orgs',
                function ($o) use ($orgId) {
                    $o->where('orgs.id', $orgId);
                }
            )->whereNotIn('jabatan', ['admin', 'Ops', 'Kepala Sekolah'])->get();

            return \response()->json([
                'nonmembers' => $nonmembers
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function storeMember(Request $request)
    {
        try {
            $orgId = $request->orgId;
            // dd($request->all());
            $org = Org::findOrFail($orgId);
            $org->members()->sync($request->data);
            return \response()->json([
                'message' => 'Anggota disimpan'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
