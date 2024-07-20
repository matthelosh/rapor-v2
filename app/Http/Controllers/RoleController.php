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
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            $role = $request->role;
            Role::updateOrCreate(
                [
                    'id' => $role['id'] ?? null,
                ],
                [
                    'name' => $role['name']
                ]
            );
            return back()->with('message', 'Peran disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function assignPermission(Request $request)
    {
        try {
            // dd($request->role['permissions']);
            $permissions = $request->role['permissions'];
            $role = Role::findOrFail($request->role['id']);
            // dd($role);
            $role->givePermissionTo($permissions);
            return back()->with('message', 'Permission assigned');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
