<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class FrontController extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Welcome',
                [
                    'posts' => Post::where('category', 'Berita')->get(),
                    'infos' => Post::where('category', 'Info')->get(),
                    'canLogin' => Route::has('login'),
                    'canRegister' => Route::has('register'),
                    'laravelVersion' => Application::VERSION,
                    'phpVersion' => PHP_VERSION,
                ]
            );
        } catch(\Exception $e) {
            throw $e;
        }

    }
}
