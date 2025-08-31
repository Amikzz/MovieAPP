<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MovieApp</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- ✅ Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-[#0f0f0f] via-[#1a1a1a] to-[#0a0a0a] text-white min-h-screen flex flex-col">

<!-- ✅ Navbar -->
<header class="w-full sticky top-0 z-50 bg-black/80 backdrop-blur-md shadow">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <a href="{{ url('/') }}"
               class="text-2xl font-extrabold tracking-tight text-white hover:text-[#e50914] transition">
                Movie<span class="text-[#e50914]">App</span>
            </a>

            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-5 py-2 bg-[#e50914] text-white rounded-lg text-sm font-semibold shadow hover:bg-[#b20710] transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2 text-sm font-semibold text-gray-200 border border-gray-600 rounded-lg hover:text-[#e50914] hover:border-[#e50914] transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-2 bg-[#e50914] text-white rounded-lg text-sm font-semibold shadow hover:bg-[#b20710] transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </div>
</header>

<!-- ✅ Main Content -->
<main class="flex-1 w-full max-w-7xl mx-auto px-6 py-10">

    <!-- Popular Movies -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        Popular <span class="text-[#e50914]">Movies</span>
    </h1>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-14">
        @foreach($popularMovies as $movie)
            <div class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300">
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                     alt="{{ $movie['title'] }}"
                     class="w-full h-full object-cover">

                <!-- Overlay on Hover -->
                <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                    <h2 class="text-lg font-semibold truncate">{{ $movie['title'] }}</h2>
                    <p class="text-sm text-gray-300">
                        ⭐ {{ $movie['vote_average'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Popular TV Shows -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        Popular <span class="text-[#e50914]">TV Shows</span>
    </h1>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($popularTvShows as $show)
            <div class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300">
                <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                     alt="{{ $show['name'] }}"
                     class="w-full h-full object-cover">

                <!-- Overlay on Hover -->
                <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                    <h2 class="text-lg font-semibold truncate">{{ $show['name'] }}</h2>
                    <p class="text-sm text-gray-300">
                        ⭐ {{ $show['vote_average'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</main>

<!-- Footer -->
<footer class="w-full text-center py-6 mt-10 border-t border-gray-700/50 text-sm text-gray-400">
    © {{ date('Y') }} MovieApp. All rights reserved.
</footer>

</body>
</html>
