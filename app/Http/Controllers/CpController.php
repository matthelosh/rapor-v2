<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use Illuminate\Http\Request;

class CpController extends Controller
{
    public function store(Request $request)
    {
        try {
            Cp::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                ],
                [
                    'fase' => $request['fase'],
                    'mapel_id' => $request['mapel_id'],
                    'teks' => $request['teks']
                ]
            );
            return back()->with('message', 'Cp disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
