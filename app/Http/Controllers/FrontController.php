<?php

namespace App\Http\Controllers;

use App\Helpers\DapodikHelper;
use App\Models\Agenda;
use App\Models\Galeri;
use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Helpers\Periode;

class FrontController extends Controller
{
    public function home(Request $request)
    {
        return Inertia::render('Welcome')->withViewData([
            'meta' => [
                'title' => 'PKG Kecamatan Wagir',
                'description' => 'Website Resmi PKG Kecamatan Wagir',
                'image' => '/img/tutwuri.png',
            ]
        ]);
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
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'Hasil Pencarian PKG Kecamatan Wagir',
                        'description' => 'Hasil pencarian Berita PKG Kecamatan Wagir',
                        'image' => '/img/tutwuri.png',
                    ]
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function berita(Request $request)
    {
        try {
            $beritas = Post::whereCategory('Berita')->paginate(8);
            if ($request->query('q')) {
                $beritas->filter(fn($berita) => \str_contains($berita->title, $request->query('q')) || \str_contains($berita->content, $request->query('q')));
            }
            return Inertia::render(
                'Front/Berita',
                [
                    'beritas' => $beritas,
                    'canLogin' => Route::has('login'),
                    'appName' => \env('APP_NAME'),
                ]
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'Berita PKG Kecamatan Wagir',
                        'description' => 'List Berita PKG Kecamatan Wagir',
                        'image' => '/img/tutwuri.png',
                    ]
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function info(Request $request)
    {
        try {
            $infos = Post::whereCategory('Info')->paginate(8);
            if ($request->query('q')) {
                $infos->filter(fn($info) => \str_contains($info->title, $request->query('q')) || \str_contains($info->content, $request->query('q')));
            }
            return Inertia::render(
                'Front/Info',
                [
                    'infos' => $infos,
                    'canLogin' => Route::has('login'),
                    'appName' => \env('APP_NAME'),
                ]
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'Pengumuman PKG Kecamatan Wagir',
                        'description' => 'List Pengumuman PKG Kecamatan Wagir',
                        'image' => '/img/tutwuri.png',
                    ]
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function galeri(Request $request)
    {
        try {
            return Inertia::render(
                'Front/Galeri',
                [
                    'canLogin' => Route::has('login'),
                    'appName' => \env('APP_NAME'),
                    'galeris' => Galeri::paginate(50),
                ]
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'Galeri PKG Kecamatan Wagir',
                        'description' => 'List Galeri PKG Kecamatan Wagir',
                        'image' => '/img/tutwuri.png',
                    ]
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function agenda(Request $request)
    {
        try {
            return Inertia::render(
                'Front/Agenda',
                [
                    'canLogin' => Route::has('login'),
                    'appName' => \env('APP_NAME'),
                    'agendas' => Agenda::whereTapel(Periode::tapel()->kode)->orderBy('mulai', 'ASC')->get(),
                ]
            )->withViewData(
                [
                    'meta' => [
                        'title' => 'Agenda Kegiatan PKG Kecamatan Wagir',
                        'description' => 'List Agenda Galeri PKG Kecamatan Wagir',
                        'image' => '/img/tutwuri.png',
                    ]
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function tesdapodik()
    {
        try {
            $users = DapodikHelper::sekolah();
            return $users;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // private function tapel()
    // {
    //     return Tapel::whereIsActive(1)->first();
    // }
}
