<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Post\PostShowResource;
use App\Http\Resources\Post\PostIndexResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('writer:id,name')->get();
        // return response()->json($posts);
        // return response()->json(['posts' => $posts]);
        return PostIndexResource::collection($posts->loadMissing(['writer:id,username', 'comments:id,post_id,user_id']));
    }

    public function show($id)
    {
        $post = Post::with('writer:id,name')->findOrFail($id);
        // return PostResource::collection($post);
        // return response()->json(['data' => $post]);

        return new PostShowResource($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $newName = null;
        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            // $fileName =
            // substr(md5(mt_rand()), 0, 7) . '-' . now()->timestamp;

            // $newName = $fileName . '.' . $extension;

            // Storage::putFileAs('posts', $request->file('image'), $newName);
            // $newName = $name . '.' . $extension;
            $newName =
                substr(md5(mt_rand()), 0, 7) . '-' . now()->timestamp . '-' . now()->timestamp . '.' . $extension;
            $request->file('image')->storeAs('posts', $newName);
        }


        // dd($fileName);

        $request['author'] = Auth::user()->id;
        $request['image'] = $newName;
        $post = Post::create($request->all());
        // $post->update(['image' => $newName]);
        return new PostShowResource($post->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required'
        ]);

        $request['author'] = Auth::user()->id;
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new PostShowResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id)
    {

        $post = Post::findOrFail($id);
        $post->delete();
        return new PostShowResource($post->loadMissing('writer:id,username'));
    }
}
