<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;

class TapelController extends Controller
{
    //

    public function toggle(Request $request, $id)
    {
        try {
            $status = $request->status;
            Tapel::whereIsActive(1)->update(['is_active' => 0]);
            $tapel = Tapel::findOrFail($id);
            $tapel->update(['is_active' => $status]);

            return back()->with('message', "Tapel " . ($status === 1 ? "Diaktifkan" : "Dinonaktifkan"));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
