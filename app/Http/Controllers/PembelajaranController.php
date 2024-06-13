<?php

namespace App\Http\Controllers;

use App\PembelajaranTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

class PembelajaranController extends Controller implements HasMiddleware
{
    use PembelajaranTrait;
   

    public function home() {
        
    }

    public static function middleware(): Array 
    {
        return [
            'role:admin',
        ];
    }
}
