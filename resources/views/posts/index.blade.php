{{--
    Post list page (GET /posts), shown by PostController@index.
    Gets $posts (one page of 7 posts from paginate()), $categories and $tags (for the dropdowns).
    author and tags are eager loaded in the controller, comments_count comes from withCount().
    {{ }} escapes the output, so HTML typed into a title can't run on the page.
--}}
@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Latest Announcements</h1>

    {{-- search/filter form. method GET puts the values in the URL: /posts?q=...&category=...&tag=... --}}
    {{-- flex-col on phones, sm:flex-row puts the fields in one row on bigger screens --}}
    <form method="GET" action="{{ route('posts.index') }}" class="flex flex-col sm:flex-row gap-2 mb-6">
        {{-- request('q') refills the box with what was searched --}}
        <input name="q" value="{{ request('q') }}" placeholder="Search title"
               class="flex-1 border border-stone-300 rounded px-3 py-2 bg-white">

        <select name="category" class="border border-stone-300 rounded px-3 py-2 bg-white">
            <option value="">All categories</option> {{-- empty value = no category filter --}}
            @foreach ($categories as $c)
                {{-- @selected keeps the chosen category selected after filtering --}}
                <option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>
            @endforeach
        </select>

        <select name="tag" class="border border-stone-300 rounded px-3 py-2 bg-white">
            <option value="">All tags</option>
            @foreach ($tags as $t)
                <option value="{{ $t }}" @selected(request('tag') === $t)>{{ $t }}</option>
            @endforeach
        </select>

        <button class="bg-emerald-700 hover:bg-emerald-800 text-white rounded px-4 py-2">Filter</button>
    </form>

    @if (request()->filled('tag'))
        <p class="text-sm mb-4">
            Showing posts tagged <strong>{{ request('tag') }}</strong> ·
            <a href="{{ route('posts.index', request()->except('tag', 'page')) }}" class="text-emerald-700 hover:underline">clear tag</a>
        </p>
    @endif

    {{-- card grid: 1 column on phones, 2 columns from md: (768px) and up --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- @forelse = @foreach with an @empty part for when there are no posts --}}
        @forelse ($posts as $post)
            {{-- pinned post gets a yellow border --}}
            <article class="bg-white rounded shadow p-4 {{ $post->is_pinned ? 'border-2 border-amber-400' : '' }}">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if ($post->is_pinned)
                        <span class="text-xs font-semibold bg-amber-400 text-amber-950 rounded px-2 py-0.5">Pinned</span>
                    @endif
                    <span class="text-xs bg-emerald-100 text-emerald-800 rounded px-2 py-0.5">{{ $post->category }}</span>
                </div>

                {{-- title links to /posts/{id} --}}
                <h2 class="text-lg font-semibold">
                    <a href="{{ route('posts.show', $post->id) }}" class="hover:underline">{{ $post->title }}</a>
                </h2>

                {{-- author name now comes from the belongsTo relation (no more "Author #3") --}}
                <p class="text-sm text-stone-500 mt-2">
                    {{ $post->published_at->format('M d, Y') }}
                    · <a href="{{ route('authors.show', $post->author) }}" class="text-emerald-700 hover:underline">{{ $post->author->name }}</a>
                    · {{ $post->views }} views
                    · {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                </p>

                {{-- tag badges, clicking one filters the list by that tag --}}
                <div class="flex flex-wrap gap-1 mt-3">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                           class="text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 rounded-full px-2 py-0.5">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </article>
        @empty
            <p class="text-stone-500">No posts match your search.</p>
        @endforelse
    </div>

    {{-- page number buttons. Laravel's built-in pagination view (already Tailwind styled) --}}
    <div class="mt-6">
        {{ $posts->links() }}
    </div>

    {{-- firstItem/lastItem = position of the first and last post on this page, total = all matching posts --}}
    {{-- sm:hidden because links() already shows "Showing x to y of z results" from sm: (640px) up --}}
    <p class="text-sm text-stone-500 mt-2 sm:hidden">
        Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} posts
    </p>
@endsection
