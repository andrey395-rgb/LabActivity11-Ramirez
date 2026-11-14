{{--
    Single post page (GET /posts/{id}), shown by PostController@show.
    Gets $post with author, tags and comments.user already loaded with load().
    The views count shown here already includes this visit.
--}}
@extends('layouts.app')

{{-- browser tab shows the post title --}}
@section('title', $post->title)

@section('content')
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Back to all posts</a>

    <article class="bg-white rounded shadow p-6 mt-4">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            @if ($post->is_pinned)
                <span class="text-xs font-semibold bg-amber-400 text-amber-950 rounded px-2 py-0.5">Pinned</span>
            @endif
            <span class="text-xs bg-emerald-100 text-emerald-800 rounded px-2 py-0.5">{{ $post->category }}</span>
            @foreach ($post->tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                   class="text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 rounded-full px-2 py-0.5">#{{ $tag->name }}</a>
            @endforeach
        </div>

        {{-- bigger title from sm: (640px) and up --}}
        <h1 class="text-2xl sm:text-3xl font-bold">{{ $post->title }}</h1>

        <p class="text-sm text-stone-500 mt-2">
            Published {{ $post->published_at->format('F d, Y') }}
            by <a href="{{ route('authors.show', $post->author) }}" class="text-emerald-700 hover:underline">{{ $post->author->name }}</a>
            ({{ $post->author->position }})
            · {{ $post->views }} views
        </p>

        {{-- body is stored as one string, paragraphs separated by a blank line ("\n\n").
             explode() splits it so each paragraph gets its own <p> --}}
        <div class="mt-6 space-y-4 leading-relaxed">
            @foreach (explode("\n\n", $post->body) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>

    {{-- ===== comments ===== --}}
    <section class="bg-white rounded shadow p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Comments ({{ $post->comments->count() }})</h2>

        @auth
            {{-- list every error at the top of the form --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-800 rounded px-4 py-3">
                    <p class="font-semibold">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6 space-y-2">
                @csrf
                <label for="body" class="block text-sm font-medium">Your comment</label>
                <textarea id="body" name="body" rows="3" placeholder="Unsa imong comment?"
                          class="w-full border rounded px-3 py-2 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button class="bg-emerald-700 hover:bg-emerald-800 text-white rounded px-4 py-2">Post comment</button>
            </form>
        @endauth

        @guest
            <p class="mb-6 text-sm bg-stone-100 rounded px-4 py-3">
                <a href="{{ route('login') }}" class="text-emerald-700 font-semibold hover:underline">Sign in to comment</a>
            </p>
        @endguest

        {{-- newest first because of ->latest() in the controller's load() --}}
        <ul class="space-y-4">
            @forelse ($post->comments as $comment)
                <li class="border-b border-stone-200 pb-3">
                    <p class="text-sm">
                        <span class="font-semibold">{{ $comment->user->name }}</span>
                        <span class="text-stone-500">· {{ $comment->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="mt-1">{{ $comment->body }}</p>
                </li>
            @empty
                <li class="text-stone-500">No comments yet. Be the first!</li>
            @endforelse
        </ul>
    </section>
@endsection
