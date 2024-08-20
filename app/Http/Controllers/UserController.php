<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        try {
            $user = User::updateOrCreate(
                [
                    'id' => $request->id ?? \null
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]
            );

            $user->assignRole($request->role);
            return \back()->with('message', 'User Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
