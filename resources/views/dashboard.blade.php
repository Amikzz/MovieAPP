<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MovieApp</title>

    <!-- ✅ Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-screen flex flex-col
             bg-gradient-to-br from-white via-gray-100 to-gray-200
             dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a]
             text-black dark:text-white
             overflow-x-hidden">

<!-- Navbar -->
<header class="w-full sticky top-0 z-50 bg-white/90 dark:bg-black/80 backdrop-blur-md shadow">
    <div class="flex items-center justify-between px-6 py-4">

        <!-- Logo -->
        <a href="{{ url('/dashboard') }}"
           class="text-2xl font-extrabold tracking-tight transition text-black dark:text-white hover:text-[#e50914]">
            Movie<span class="text-[#e50914]">App</span>
        </a>

        <!-- Centered Navigation Links -->
        <nav class="hidden md:flex gap-8 mx-auto">
            <a href="{{ url('/dashboard') }}"
               class="font-medium transition
                {{ request()->is('dashboard') ? 'text-[#e50914]' : 'text-black dark:text-white' }}">
                Home
            </a>

            <a href=""
               class="text-black dark:text-white font-medium hover:text-[#e50914] transition">
                Movies
            </a>
            <a href=""
               class="text-black dark:text-white font-medium hover:text-[#e50914] transition">
                TV Shows
            </a>
            <a href=""
               class="text-black dark:text-white font-medium hover:text-[#e50914] transition">
                Genres
            </a>
            <a href=""
               class="text-black dark:text-white font-medium hover:text-[#e50914] transition">
                Actors
            </a>
            <a href=""
               class="text-black dark:text-white font-medium hover:text-[#e50914] transition">
                Favorites
            </a>
        </nav>

        @auth
            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <!-- Button -->
                <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-[#e50914] text-white rounded-full shadow-md hover:bg-[#b20710] transition transform hover:scale-105 focus:outline-none">
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 transform transition-transform duration-200"
                         :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-cloak @click.away="open = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 transform -translate-y-2 scale-95"
                     class="absolute right-0 mt-3 w-52 bg-white dark:bg-gray-900 text-black dark:text-white rounded-xl shadow-2xl overflow-hidden z-50 border border-red-600">

                    <!-- Menu Items -->
                    <a href="{{ route('settings.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.968 7.968 0 0112 15c1.657 0 3.168.504 4.379 1.358m1.242 1.446A8.003 8.003 0 015.121 17.804m0 0A7.964 7.964 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8a7.964 7.964 0 01-1.121 5.804z"/>
                        </svg>
                        My Profile
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3z"/>
                        </svg>
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition text-left">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <nav class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                   class="px-5 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:text-[#e50914] transition">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-5 py-2 bg-[#e50914] text-white rounded-lg text-sm font-semibold shadow hover:bg-[#b20710] transition">
                        Register
                    </a>
                @endif
            </nav>
        @endguest
    </div>
</header>

<main class="flex-1 w-full max-w-[1600px] mx-auto px-10 py-10">

    <!-- Popular Movies -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        Popular <span class="text-[#e50914]">Movies</span>
    </h1>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6 mb-14">
        @foreach($popularMovies as $movie)
            <a href=""
               class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                     alt="{{ $movie['title'] }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0
                            bg-black/70 dark:bg-black/70
                            group-hover:bg-black/50 dark:group-hover:bg-black/50
                            opacity-0 group-hover:opacity-100
                            transition flex flex-col justify-end p-4">
                    <h2 class="text-lg font-semibold truncate text-white dark:text-white">
                        {{ $movie['title'] }}
                    </h2>
                    <p class="text-sm text-white dark:text-white">
                        ⭐ {{ $movie['vote_average'] }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Popular TV Shows -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        Popular <span class="text-[#e50914]">TV Shows</span>
    </h1>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6">
        @foreach($popularTvShows as $show)
            <a href=""
               class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                     alt="{{ $show['name'] }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                    <h2 class="text-lg font-semibold truncate">{{ $show['name'] }}</h2>
                    <p class="text-sm text-gray-300">⭐ {{ $show['vote_average'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

</main>

    <!-- ✅ Modern Footer -->
    <footer class="w-full bg-black/90 backdrop-blur-md py-8 mt-10 border-t border-gray-700/50 text-gray-400">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- Left: Copyright -->
            <p class="text-sm md:text-base">
                © {{ date('Y') }} <span class="font-semibold text-white">MovieApp</span>. All rights reserved.
            </p>

            <!-- Right: Social / Links -->
            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-400 hover:text-[#e50914] transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.28 4.28 0 001.88-2.37 8.5 8.5 0 01-2.7 1.03 4.27 4.27 0 00-7.28 3.9A12.1 12.1 0 013 4.8a4.27 4.27 0 001.32 5.7 4.2 4.2 0 01-1.94-.54v.05a4.27 4.27 0 003.42 4.19 4.3 4.3 0 01-1.93.07 4.27 4.27 0 003.99 2.96A8.57 8.57 0 012 19.54a12.06 12.06 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19-.01-.37-.02-.56A8.64 8.64 0 0024 5.1a8.35 8.35 0 01-2.54.7z"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-[#e50914] transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 4.41 2.86 8.16 6.84 9.49v-6.72h-2.06V12h2.06V9.8c0-2.04 1.21-3.16 3.06-3.16.89 0 1.82.16 1.82.16v2h-1.03c-1.01 0-1.32.63-1.32 1.27V12h2.25l-.36 2.77h-1.89v6.72a9.953 9.953 0 006.84-9.49c0-5.5-4.46-9.96-9.96-9.96z"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-[#e50914] transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.22 5.72c-.77.34-1.6.58-2.46.69a4.27 4.27 0 001.88-2.37 8.5 8.5 0 01-2.7 1.03 4.27 4.27 0 00-7.28 3.9A12.1 12.1 0 013 4.8a4.27 4.27 0 001.32 5.7 4.2 4.2 0 01-1.94-.54v.05a4.27 4.27 0 003.42 4.19 4.3 4.3 0 01-1.93.07 4.27 4.27 0 003.99 2.96A8.57 8.57 0 012 19.54a12.06 12.06 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19-.01-.37-.02-.56A8.64 8.64 0 0024 5.1a8.35 8.35 0 01-2.54.7z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
