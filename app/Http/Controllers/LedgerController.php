<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Mapel;
use App\NilaiTrait;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    use NilaiTrait;

    public function home(Request $request) {
        return Inertia::render("Dash/Ledger", [
            'mapels' => Mapel::all(),
            'nilais' => $this->ledger($request),
        ]);
    }
}
