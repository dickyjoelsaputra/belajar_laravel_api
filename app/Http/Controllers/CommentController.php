<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Comment\CommentResource;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);

        $request['user_id'] = Auth::user()->id;
        $comment = Comment::create($request->all());

        return new CommentResource($comment->loadMissing(['user_comment:id,username']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);

        $comment = Comment::findOrFail($id);
        // $request['user_id'] = Auth::user()->id;
        // $request['user_id'] = $comment->user_id;
        $comment->update($request->only('comments_content'));

        return new CommentResource($comment->loadMissing(['user_comment:id,username']));
    }
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return new CommentResource($comment->loadMissing(['user_comment:id,username']));
    }
}
