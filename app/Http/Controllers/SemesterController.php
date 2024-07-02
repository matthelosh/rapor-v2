<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            Semester::updateOrCreate(
                [
                    'id' => $request->data['id'] ?? null,
                ],
                [
                    'kode' => $request->data['kode'],
                    'label' => $request->data['label'],
                    'deskripsi' => $request->data['deskripsi']
                ]
            );

            return back()->with('message', 'Data Semester Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

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
