<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    // POST /posts/{post}/comments
    // StoreCommentRequest already validated the body before we get here
    public function store(StoreCommentRequest $request, Post $post)
    {
        // create through the relationship so post_id is filled in automatically
        $post->comments()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('status', 'Comment posted.');
    }
}
