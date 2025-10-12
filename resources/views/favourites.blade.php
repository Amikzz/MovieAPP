<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Favorites - MovieApp</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script> tailwind.config = {darkMode: 'media'} </script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>
    <style>
        body {
            font-family: 'instrument-sans', sans-serif;
        }

        .loader {
            border: 4px solid #ddd;
            border-top: 4px solid #e50914;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-gray-200 text-black
             dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a] dark:text-white">

<!-- Loader -->
<div id="loader"
     class="fixed inset-0 flex items-center justify-center bg-white dark:bg-black z-[100] transition-opacity duration-500">
    <div class="loader"></div>
</div>

<!-- Navbar -->
<header class="w-full sticky top-0 z-50 bg-white/90 dark:bg-black/80 backdrop-blur-md shadow">
    <div class="flex items-center justify-between px-6 py-4">
        <a href="{{ url('/dashboard') }}"
           class="text-2xl font-extrabold tracking-tight transition text-black dark:text-white hover:text-[#e50914]">
            Movie<span class="text-[#e50914]">App</span>
        </a>

        <nav class="hidden md:flex gap-8 mx-auto">
            <a href="{{ url('/dashboard') }}"
               class="font-bold {{ request()->is('dashboard') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">Home</a>
            <a href="{{ url('/movies') }}"
               class="font-bold {{ request()->is('movies*') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">Movies</a>
            <a href="{{ url('/tv-shows') }}"
               class="font-bold {{ request()->is('tv-shows*') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">TV
                Shows</a>

            <div x-data="{ open: false }" class="relative">
                <a href="#" @mouseenter="open = true" @mouseleave="open = false"
                   class="font-bold {{ request()->is('genre*') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">
                    Genres
                </a>
                <div x-show="open" x-cloak @mouseenter="open = true" @mouseleave="open = false"
                     class="absolute left-0 mt-2 min-w-[18rem] max-w-[24rem] bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50 p-4 grid grid-cols-2 gap-2 border border-gray-200 dark:border-gray-700">
                    @foreach($popularGenres as $genre)
                        <a href="{{ url('/tv-genre/'.$genre['id']) }}"
                           class="block px-3 py-2 rounded hover:bg-[#e50914] hover:text-white transition font-bold whitespace-nowrap">
                            {{ $genre['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ url('/actors') }}"
               class="font-bold {{ request()->is('actors*') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">Actors</a>
            <a href="{{ url('/favorites') }}"
               class="font-bold {{ request()->is('favorites*') ? 'text-[#e50914]' : 'text-black dark:text-white hover:text-[#e50914]' }}">Favorites</a>
        </nav>

        @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-[#e50914] text-white rounded-full shadow-md hover:bg-[#b20710] transition">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" :class="{'rotate-180': open}" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.away="open = false"
                     class="absolute right-0 mt-3 w-52 bg-white dark:bg-gray-900 text-black dark:text-white rounded-xl shadow-2xl overflow-hidden z-50 border border-red-600">
                    <a href="{{ route('settings.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5.121 17.804A7.968 7.968 0 0112 15c1.657 0 3.168.504 4.379 1.358m1.242 1.446A8.003 8.003 0 015.121 17.804z"/>
                        </svg>
                        My Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>

<main id="pageContent" class="w-full relative opacity-0 translate-y-4 transition-all duration-700 mt-20">
    <div class="max-w-7xl mx-auto px-8 mt-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">My Favorites</h1>
        <p class="text-gray-700 dark:text-gray-300">Your collection of favorite Movies and TV Shows.</p>
    </div>

    <div class="max-w-7xl mx-auto px-8 py-8 mt-8">
        @if(count($favorites) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($favorites as $fav)
                    <div
                        class="relative group rounded-xl overflow-hidden shadow-lg hover:scale-105 transform transition duration-300">
                        <img
                            src="{{ $fav['poster_path'] ? 'https://image.tmdb.org/t/p/w500'.$fav['poster_path'] : 'https://via.placeholder.com/500x750?text=No+Image' }}"
                            alt="{{ $fav['title'] ?? $fav['name'] }}" class="w-full h-72 object-cover rounded-xl">

                        <div
                            class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-xl">
                            <h3 class="text-md md:text-lg font-semibold truncate text-white">{{ $fav['title'] ?? $fav['name'] }}</h3>
                            <p class="text-sm text-white mb-2">⭐ {{ $fav['vote_average'] ?? 'N/A' }} / 10</p>
                            <div class="flex gap-2">
                                @if($fav['type'] === 'movie')
                                    <a href="{{ route('movies.show', ['id' => $fav['id']]) }}"
                                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow hover:scale-105 transition">
                                        <i data-feather="film"></i> <span>View Movie</span>
                                    </a>
                                @else
                                    <a href="{{ route('tv.show', ['id' => $fav['id']]) }}"
                                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white font-semibold shadow hover:scale-105 transition">
                                        <i data-feather="tv"></i> <span>View TV Show</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400 text-center">You haven't added any favorites yet.</p>
        @endif
    </div>
</main>

<script>
    window.addEventListener("load", () => {
        const loader = document.getElementById("loader");
        const content = document.getElementById("pageContent");
        loader.classList.add("opacity-0");
        setTimeout(() => {
            loader.style.display = "none";
            content.classList.remove("opacity-0", "translate-y-4");
            feather.replace();
        }, 500);
    });
</script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
