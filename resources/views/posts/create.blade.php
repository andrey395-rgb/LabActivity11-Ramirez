{{--
    New post form (GET /posts/create), shown by PostController@create.
    Gets $categories and $tags. Submits to POST /posts -> StorePostRequest checks it.
    old() puts back what was typed if validation fails.
--}}
@extends('layouts.app')

@section('title', 'New post')

@section('content')
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Back to all posts</a>

    <div class="bg-white rounded shadow p-6 mt-4 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Write a new post</h1>

        {{-- error summary --}}
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

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1">Title</label>
                <input id="title" name="title" value="{{ old('title') }}"
                       class="w-full border rounded px-3 py-2 @error('title') border-red-500 @else border-stone-300 @enderror">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium mb-1">Category</label>
                <select id="category" name="category"
                        class="w-full border rounded px-3 py-2 bg-white @error('category') border-red-500 @else border-stone-300 @enderror">
                    <option value="">-- choose --</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium mb-1">Body</label>
                <textarea id="body" name="body" rows="6"
                          class="w-full border rounded px-3 py-2 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
                <p class="text-xs text-stone-500">At least 20 characters. Leave a blank line between paragraphs.</p>
                @error('body')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend class="block text-sm font-medium mb-1">Tags</legend>
                <div class="flex flex-wrap gap-3">
                    @foreach ($tags as $tag)
                        <label class="flex items-center gap-1 text-sm">
                            {{-- old('tags', []) is the array of checked ids, so they stay checked after an error --}}
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, old('tags', [])))>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('tags.*')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </fieldset>

            <div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))>
                    Publish now (leave unchecked to save as draft)
                </label>
                @error('is_published')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-emerald-700 hover:bg-emerald-800 text-white rounded px-4 py-2">Save post</button>
        </form>
    </div>
@endsection
