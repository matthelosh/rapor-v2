<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;

class TapelController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            Tapel::updateOrCreate(
                [
                    'id' => $request->data['id'] ?? null
                ],
                [
                    'kode' => $request->data['kode'],
                    'label' => $request->data['label'],
                    'deskripsi' => $request->data['deskripsi']
                ]
            );

            return back()->with('message', 'Tapel Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
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
