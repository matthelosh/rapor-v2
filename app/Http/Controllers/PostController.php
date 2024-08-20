<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home(Request $request)
    {
        try {
            $posts = Post::with('author.userable')->orderBy('updated_at', 'DESC')->paginate(15);

            return Inertia::render(
                'Dash/Post',
                [
                    'posts' => $posts
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listFiles()
    {
        $files = Storage::disk('public')->allFiles('post');
        return response()->json([
            'files' => $files
        ]);
    }

    public function uploadImage(Request $request)
    {
        try {
            $image = $request->file('image');
            $store = Storage::putFileAs('public/post', $image, $image->getClientOriginalName() . '.' . $image->extension());
            return response()->json(
                [
                    'url' => Storage::url($store)
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the post read page.
     */
    public function read(Request $request, $slug)
    {
        try {
            $post = Post::whereSlug($slug)->with('author.userable')->first();
            $posts = Post::whereCategory($post->category)->whereNot('id', $post->id)->get();
            return Inertia::render(
                'Front/Read',
                [
                    'post' => $post,
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
                        'title' => 'PKG Kecamatan Wagir',
                        'description' => substr($post->content, 0, 200),
                        'image' => $post->cover,
                        'url' => $request->url()
                    ]
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            $post = Post::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    'cover'  => $data['cover'],
                    'category' => $data['category'],
                    'type' => $data['type'],
                    'slug' => strtolower(str_replace(" ", "-", $data['title'])),
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'user_id' => $request->user()->id,
                    'status' => 'published'
                ]
            );

            return back()->with('message', 'Tulisan disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
