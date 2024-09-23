<?php

namespace App\Http\Controllers;

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
}
