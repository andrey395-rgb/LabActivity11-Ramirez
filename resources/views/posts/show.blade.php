{{--
    Single post page (GET /posts/{id}), shown by PostController@show.
    Gets $post. The views count shown here already includes this visit.
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
        </div>

        {{-- bigger title from sm: (640px) and up --}}
        <h1 class="text-2xl sm:text-3xl font-bold">{{ $post->title }}</h1>

        <p class="text-sm text-stone-500 mt-2">
            Published {{ $post->published_at->format('F d, Y') }}
            · Author #{{ $post->author_id }}
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
@endsection
