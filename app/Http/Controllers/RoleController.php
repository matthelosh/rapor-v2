<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render('Dash/Role', [
                'roles' => Role::with('permissions')->get(),
                'permissions' => Permission::all(),
                'users' => User::with('roles', 'permissions')->get()
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
