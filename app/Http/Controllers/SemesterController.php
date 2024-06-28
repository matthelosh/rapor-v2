<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    //

    public function toggle(Request $request, $id)
    {
        try {
            $status = $request->status;
            Semester::whereIsActive(1)->update(['is_active' => 0]);
            $semester = Semester::findOrFail($id);
            $semester->update(['is_active' => $status]);

            return back()->with('message', "Semester " . ($status === 1 ? "Diaktifkan" : "Dinonaktifkan"));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
