<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'List Data Posts', $posts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'   => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        return new PostResource(true, 'Data Post ditambahkan', $post);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if ($post) {
            return new PostResource(true, 'Detail Post', $post);
        } else {
            return response()->json(['success' => true, 'message' => 'Data Post tidak ditemukan', 'data' => $post], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/' . basename($post->image));

            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);
        }

        return new PostResource(true, 'Post diperbarui', $post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        Storage::delete('public/posts/' . basename($post->image));
        $post->delete();

        return new PostResource(true, 'Data Post dihapus', null);
    }


}
