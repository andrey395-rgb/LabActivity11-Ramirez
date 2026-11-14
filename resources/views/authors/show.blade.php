{{--
    Author page (GET /authors/{author}), shown by AuthorController@show.
    Gets $author, $posts (their published posts) and $commentsReceived (hasManyThrough count).
--}}
@extends('layouts.app')

@section('title', $author->name)

@section('content')
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Back to all posts</a>

    <div class="bg-white rounded shadow p-6 mt-4">
        <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
        <p class="text-stone-600">{{ $author->position }}</p>
        <p class="text-sm text-stone-500 mt-2">
            {{ $posts->count() }} published {{ Str::plural('post', $posts->count()) }}
            · {{ $commentsReceived }} comments received
        </p>
    </div>

    <h2 class="text-xl font-semibold mt-6 mb-3">Posts by {{ $author->name }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($posts as $post)
            <article class="bg-white rounded shadow p-4 {{ $post->is_pinned ? 'border-2 border-amber-400' : '' }}">
                <span class="text-xs bg-emerald-100 text-emerald-800 rounded px-2 py-0.5">{{ $post->category }}</span>
                <h3 class="text-lg font-semibold mt-2">
                    <a href="{{ route('posts.show', $post->id) }}" class="hover:underline">{{ $post->title }}</a>
                </h3>
                <p class="text-sm text-stone-500 mt-1">
                    {{ $post->published_at->format('M d, Y') }}
                    · {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                </p>
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                           class="text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 rounded-full px-2 py-0.5">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </article>
        @empty
            <p class="text-stone-500">This author has no published posts yet.</p>
        @endforelse
    </div>
@endsection
