<?php

namespace App\Http\Controllers;

use App\Models\Elemen;
use App\Models\Tp;
use Inertia\Inertia;
use App\Models\Mapel;
use App\PembelajaranTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PembelajaranController extends Controller implements HasMiddleware
{
    use PembelajaranTrait;
   

    public function home() {
        return Inertia::render('Dash/Pembelajaran', [
            'mapels' => Mapel::with('tps')->get(),
            'elemens' => Elemen::all(),
        ]);
    }

    public static function middleware(): Array 
    {
        return [
            'role:admin',
        ];
    }
}
