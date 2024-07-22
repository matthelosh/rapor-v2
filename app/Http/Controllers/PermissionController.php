<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $permission = new Permission($request->permission);
            $permission->save();

            return \back()->with('message', "Izin ditambahkan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
