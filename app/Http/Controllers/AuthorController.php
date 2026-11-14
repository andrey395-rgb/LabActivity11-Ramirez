<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    // GET /authors/{author}
    public function show(Author $author)
    {
        // only published posts, newest first, with tags + comment count for the cards
        $posts = $author->posts()
            ->where('is_published', true)
            ->with('tags')
            ->withCount('comments')
            ->latest('published_at')
            ->get();

        // hasManyThrough: all comments on this author's posts (Author -> Post -> Comment)
        $commentsReceived = $author->comments()->count();

        return view('authors.show', compact('author', 'posts', 'commentsReceived'));
    }
}
