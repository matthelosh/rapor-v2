<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Sekolah;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class FrontController extends Controller
{
    public function home(Request $request)
    {
        try {
            $tapel = $this->tapel()->kode;
            return Inertia::render(
                'Welcome',
                [
                    'posts' => Post::where('category', 'Berita')->get(),
                    'infos' => Post::where('category', 'Info')->get(),
                    'canLogin' => Route::has('login'),
                    'canRegister' => Route::has('register'),
                    'sekolahs' => Sekolah::with(
                        [
                            'gurus' => function ($g) {
                                $g->whereNot('jabatan', 'ops');
                            },
                            'rombels.siswas' => function ($q) use ($tapel) {
                                $q->where('tapel', $tapel);
                            },
                            'siswas' => fn($s) => $s->whereStatus('aktif')
                        ]
                    )->get(),
                    'appName' => \env('APP_NAME'),
                    'laravelVersion' => Application::VERSION,
                    'phpVersion' => PHP_VERSION,
                ]
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'PKG Kecamatan Wagir',
                        'description' => 'Website Resmi PKG Kecamatan Wagir',
                        'image' => asset('img/tutwuri.png'),
                    ]
                ]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function cari(Request $request)
    {
        try {
            $q = $request->query('q');
            $posts = Post::where('content', 'LIKE', '%' . $q . '%')
                ->orWhere('title', 'LIKE', '%' . $q . '%')
                ->get();
            return Inertia::render(
                'Front/Cari',
                [
                    'posts' => $posts,
                    'canLogin' => Route::has('login'),
                    'canRegister' => Route::has('register'),
                    'appName' => \env('APP_NAME'),
                    'laravelVersion' => Application::VERSION,
                    'phpVersion' => PHP_VERSION,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
}
