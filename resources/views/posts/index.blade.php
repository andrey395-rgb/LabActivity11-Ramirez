{{--
    Post list page (GET /posts), shown by PostController@index.
    Gets $posts (one page of 7 posts from paginate()) and $categories (for the dropdown).
    {{ }} escapes the output, so HTML typed into a title can't run on the page.
--}}
@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Latest Announcements</h1>

    {{-- search/filter form. method GET puts the values in the URL: /posts?q=...&category=... --}}
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

        <button class="bg-emerald-700 hover:bg-emerald-800 text-white rounded px-4 py-2">Filter</button>
    </form>

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

                {{-- published_at is a date object because of the cast in the Post model --}}
                {{-- author id only for now, author names need relationships (Week 11) --}}
                <p class="text-sm text-stone-500 mt-2">
                    {{ $post->published_at->format('M d, Y') }}
                    · Author #{{ $post->author_id }}
                    · {{ $post->views }} views
                </p>
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
    <p class="text-sm text-stone-500 mt-2">
        Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} posts
    </p>
@endsection
