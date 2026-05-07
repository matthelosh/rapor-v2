<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function home(Request $request)
    {
        try {
            $workshops = Workshop::all();

            return $workshops;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
