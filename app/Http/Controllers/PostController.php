<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

/*
 * PostController: handles the two pages of the bulletin.
 *   index() -> GET /posts       list of published posts (search, filter, pagination)
 *   show()  -> GET /posts/{id}  one post, and adds 1 to its view count
 * Routes are in routes/web.php.
 */
class PostController extends Controller
{
    // posts per page: Ramirez = 7 letters (PDF says use 6 only if the name is shorter than 6)
    const PER_PAGE = 7;

    // GET /posts  (also /posts?q=...&category=...&page=...)
    public function index(Request $request)
    {
        $posts = Post::query()                       // start a query on the posts table
            ->where('is_published', true)            // hide drafts

            // when() only adds the condition if the first argument is true.
            // filled('q') is true only when ?q= is in the URL AND not empty.
            ->when($request->filled('q'), function ($query) use ($request) {
                // title LIKE '%word%' = title contains the search word.
                // The value is passed as a binding, not glued into the SQL string,
                // so it's safe from SQL injection.
                $query->where('title', 'like', '%' . $request->input('q') . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->input('category')); // exact category match
            })

            ->orderByDesc('is_pinned')   // pinned posts (1) before normal posts (0)
            ->latest('published_at')     // then newest published date first
            ->paginate(self::PER_PAGE)   // LIMIT 7 + OFFSET from ?page=, and counts the total
            ->withQueryString();         // keep ?q= and ?category= on the page 2, 3... links

        // list of categories for the dropdown, taken from the database (no duplicates, A-Z)
        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');

        // send both variables to resources/views/posts/index.blade.php
        return view('posts.index', compact('posts', 'categories'));
    }

    // GET /posts/{id}
    public function show($id)
    {
        // find the post by id, or stop with a 404 page if the id doesn't exist
        $post = Post::findOrFail($id);

        // drafts are not public yet
        if (! $post->is_published) {
            abort(404);
        }

        // ActiveRecord: change the attribute on the object, then save() runs the UPDATE
        $post->views = $post->views + 1;
        $post->save();

        // send the post to resources/views/posts/show.blade.php
        return view('posts.show', compact('post'));
    }
}
