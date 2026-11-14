<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

/*
 * PostController: pages of the bulletin.
 *   index()  -> GET  /posts         list of published posts (search, filter, pagination)
 *   create() -> GET  /posts/create  form for a new post (authors only)
 *   store()  -> POST /posts         saves the new post
 *   show()   -> GET  /posts/{id}    one post with its comments, and adds 1 to its view count
 * Routes are in routes/web.php.
 */
class PostController extends Controller
{
    // posts per page: Ramirez = 7 letters (PDF says use 6 only if the name is shorter than 6)
    const PER_PAGE = 7;

    // GET /posts  (also /posts?q=...&category=...&tag=...&page=...)
    public function index(Request $request)
    {
        $posts = Post::query()                       // start a query on the posts table
            ->with(['author', 'tags'])               // Lab 11: eager load, 3 queries total instead of 1 per post (N+1)
            ->withCount('comments')                  // adds $post->comments_count without loading the comments
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
            // ?tag=Urgent -> only posts that have a tag with that name
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->where('name', $request->input('tag'));
                });
            })

            ->orderByDesc('is_pinned')   // pinned posts (1) before normal posts (0)
            ->latest('published_at')     // then newest published date first
            ->paginate(self::PER_PAGE)   // LIMIT 7 + OFFSET from ?page=, and counts the total
            ->withQueryString();         // keep ?q=, ?category= and ?tag= on the page 2, 3... links

        // list of categories for the dropdown, taken from the database (no duplicates, A-Z)
        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');

        // tag names for the tag filter dropdown
        $tags = Tag::orderBy('name')->pluck('name');

        // send the variables to resources/views/posts/index.blade.php
        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    // GET /posts/create  (auth middleware, only authors see the link)
    public function create()
    {
        $categories = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Livelihood'];
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    // POST /posts
    // StorePostRequest does the authorize() (has author profile) and rules() before this runs
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $isPublished = $request->boolean('is_published'); // unchecked checkbox = not sent = false

        // create through the author's posts relationship so author_id is filled in for us.
        // tags are not a column in posts, so leave them out of create()
        $post = $request->user()->author->posts()->create([
            ...collect($data)->except('tags')->all(),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        // put the checked tags on the post (post_tag pivot)
        $post->tags()->sync($data['tags']);

        // drafts can't be opened yet (show() gives 404), so go back to the list instead
        if (! $isPublished) {
            return redirect()->route('posts.index')->with('status', 'Draft saved. It will not show on the bulletin yet.');
        }

        return redirect()->route('posts.show', $post->id)->with('status', 'Post published.');
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

        // eager load everything the page shows. comments.user = each comment's writer,
        // without it the @foreach would run 1 query per comment (N+1)
        $post->load([
            'author',
            'tags',
            'comments' => fn ($query) => $query->latest(), // newest comment first
            'comments.user',
        ]);

        // send the post to resources/views/posts/show.blade.php
        return view('posts.show', compact('post'));
    }
}
