{{--
    Main layout: the shared HTML shell (head, header, footer) for every page.
    Pages use @extends('layouts.app') and put their content in @section('content').
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- @yield('title') is filled by each page's @section('title') --}}
    <title>@yield('title', 'Home') | Barangay Bulletin – Purok Ramirez</title>

    {{-- loads the Tailwind CSS and JS built by Vite (npm run dev / npm run build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 min-h-screen flex flex-col">

    {{-- header: stacked on phones, side by side from sm: (640px) and up --}}
    <header class="bg-emerald-800 text-white">
        <div class="max-w-5xl mx-auto px-4 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <a href="{{ route('posts.index') }}" class="text-xl font-bold">Barangay Bulletin – Purok Ramirez</a>
                <p class="text-sm text-emerald-100">Barangay Matina Crossing, Davao City</p>
            </div>

            {{-- nav from Lab 9: @auth shows name + logout, @guest shows sign in --}}
            <nav class="flex flex-wrap items-center gap-3 text-sm">
                @auth
                    {{-- only accounts with an author profile (my Kagawad) can write posts --}}
                    @if (auth()->user()->author)
                        <a href="{{ route('posts.create') }}" class="bg-white text-emerald-800 font-semibold rounded px-3 py-1 hover:bg-emerald-50">+ New post</a>
                    @endif
                    <span>Hi, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="underline">Logout</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="underline">Sign in</a>
                @endguest
            </nav>
        </div>
    </header>

    {{-- each page's content goes here --}}
    <main class="max-w-5xl w-full mx-auto px-4 py-6 flex-1">
        {{-- flash message, e.g. "Comment posted." after redirect --}}
        @if (session('status'))
            <div class="mb-4 bg-emerald-100 border border-emerald-300 text-emerald-900 rounded px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-stone-200 text-center text-sm text-stone-600 py-4">
        &copy; {{ date('Y') }} Barangay Matina Crossing Hall
    </footer>

</body>
</html>
