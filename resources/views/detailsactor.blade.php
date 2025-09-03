<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $actor['name'] ?? 'Actor Details' }} - MovieApp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {darkMode: 'media'}
    </script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Fonts -->
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
<div id="loader" class="fixed inset-0 flex items-center justify-center
    bg-white dark:bg-black z-[100] transition-opacity duration-500">
    <div class="loader"></div>
</div>

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
               class="font-bold transition
        {{ request()->is('dashboard') ? 'text-[#e50914]' : 'text-black dark:text-white' }}">
                Home
            </a>

            <a href="{{ url('/movies') }}"
               class="{{ request()->is('movies*') ? 'text-[#e50914]' : 'text-black dark:text-white' }} font-bold hover:text-[#e50914] transition">
                Movies
            </a>

            <a href="{{ url('/tv-shows') }}"
               class="{{ request()->is('tv-shows*') ? 'text-[#e50914]' : 'text-black dark:text-white' }} font-bold hover:text-[#e50914] transition">
                TV Shows
            </a>

            <!-- Navbar Genres Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <a href="#"
                   @mouseenter="open = true"
                   @mouseleave="open = false"
                   class="text-black dark:text-white font-bold hover:text-[#e50914] transition cursor-pointer">
                    Genres
                </a>
                <div x-show="open"
                     x-cloak
                     @mouseenter="open = true"
                     @mouseleave="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute left-0 mt-2 min-w-[18rem] max-w-[24rem] bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50 p-4 grid grid-cols-2 gap-2 border border-gray-200 dark:border-gray-700"
                >
                    @foreach($popularGenres as $genre)
                        <a href="{{ url('/genre/'.$genre['id']) }}"
                           class="block px-3 py-2 rounded hover:bg-[#e50914] hover:text-white text-gray-700 dark:text-gray-200 transition font-bold whitespace-nowrap min-w-max">
                            {{ $genre['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ url('/actors') }}"
               class="{{ request()->is('actors*') || request()->is('actor/*') ? 'text-[#e50914]' : 'text-black dark:text-white' }} font-bold hover:text-[#e50914] transition">
                Actors
            </a>

            <a href="{{ url('/favorites') }}"
               class="{{ request()->is('favorites*') ? 'text-[#e50914]' : 'text-black dark:text-white' }} font-bold hover:text-[#e50914] transition">
                Favorites
            </a>
        </nav>

    @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-[#e50914] text-white rounded-full shadow-md hover:bg-[#b20710] transition transform hover:scale-105 focus:outline-none">
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 transform transition-transform duration-200"
                         :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.away="open = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 transform -translate-y-2 scale-95"
                     class="absolute right-0 mt-3 w-52 bg-white dark:bg-gray-900 text-black dark:text-white rounded-xl shadow-2xl overflow-hidden z-50 border border-red-600">

                    <a href="{{ route('settings.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5.121 17.804A7.968 7.968 0 0112 15c1.657 0 3.168.504 4.379 1.358m1.242 1.446A8.003 8.003 0 015.121 17.804m0 0A7.964 7.964 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8a7.964 7.964 0 01-1.121 5.804z"/>
                        </svg>
                        My Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition text-left">
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

<main id="pageContent" class="w-full relative opacity-0 translate-y-4 transition-all duration-700">

    <!-- Actor Profile Section -->
    <div class="max-w-7xl mx-auto px-6 mt-12 grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

        <!-- Actor Info Card -->
        <div class="md:col-span-2 space-y-6 animate-fadeUp delay-200 backdrop-blur-md p-6 rounded-2xl shadow-lg">
            <!-- Actor Name -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white">
                {{ $actor['name'] }}
            </h1>

            <!-- Biography / Description -->
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $actor['biography'] ? \Illuminate\Support\Str::limit($actor['biography'], 300, '...') : 'No biography available.' }}
            </p>

            <!-- Actor Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4 text-gray-800 dark:text-gray-200">
                <div class="flex items-center gap-3">
                    <i data-feather="calendar" class="text-[#e50914]"></i>
                    <span><strong>Birthday:</strong> {{ $actor['birthday'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-feather="map-pin" class="text-[#e50914]"></i>
                    <span><strong>Place of Birth:</strong> {{ $actor['place_of_birth'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-feather="star" class="text-[#e50914]"></i>
                    <span><strong>Popularity:</strong> {{ $actor['popularity'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-feather="film" class="text-[#e50914]"></i>
                    <span><strong>Known For:</strong> {{ $actor['known_for_department'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Actor Image -->
        <div class="flex justify-center md:justify-end animate-fadeIn delay-300">
            <img src="https://image.tmdb.org/t/p/w300{{ $actor['profile_path'] ?? '' }}"
                 alt="{{ $actor['name'] }}"
                 class="w-56 md:w-52 rounded-3xl shadow-xl object-cover transition-transform duration-300 hover:scale-105">
        </div>
    </div>

    <h2 class="text-3xl md:text-4xl font-extrabold text-red-600 mt-12 text-center">
        Movies & Credits
    </h2>

    <!-- Movies Grid -->
    <div class="max-w-7xl mx-auto px-8 py-8 mt-8">
        @if($credits->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($credits as $credit)
                    @php
                        $type = 'movie';
                        $itemId = $credit['id'];
                        $isFavorited = auth()->check()
                            ? auth()->user()->favorites()->where('type', $type)->where('item_id', $itemId)->exists()
                            : false;
                    @endphp

                        <!-- Movie Card -->
                    <div
                        class="relative group rounded-xl overflow-hidden shadow-lg hover:scale-105 transform transition duration-300">
                        <!-- Poster -->
                        <img src="https://image.tmdb.org/t/p/w500{{ $credit['poster_path'] ?? '' }}"
                             alt="{{ $credit['title'] ?? 'Untitled' }}"
                             class="w-full h-72 object-cover rounded-xl">

                        <!-- Hover Overlay -->
                        <div
                            class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-xl">
                            <h3 class="text-md md:text-lg font-semibold truncate text-white">
                                {{ $credit['title'] ?? 'Untitled' }}
                            </h3>
                            <p class="text-sm text-white mb-2">
                                ⭐ {{ $credit['vote_average'] ?? 'N/A' }} / 10
                            </p>

                            <div class="flex gap-2">
                                <!-- View Button -->
                                <a href="{{ route('movies.show', ['id' => $credit['id']]) }}"
                                   class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12H9m6 0l-3-3m3 3l-3 3"/>
                                    </svg>
                                    <span>View</span>
                                </a>

                                <!-- Favorite Button -->
                                @auth
                                    <button
                                        x-data="{ favorited: @json($isFavorited), loading: false }"
                                        x-on:click="
                                        loading = true;
                                        fetch(`/favorites/toggle/{{ $type }}/{{ $itemId }}`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({})
                                        })
                                        .then(res => res.json())
                                        .then(data => favorited = data.favorited)
                                        .catch(err => { console.error(err); alert('Failed to add/remove favorite'); })
                                        .finally(() => loading = false)
                                    "
                                        class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-gradient-to-r from-[#e50914] to-[#ff3d5f] text-white font-semibold shadow hover:scale-105 transition"
                                    >
                                        <svg x-show="!favorited" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                             fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M4 8c0-3.2 4-6 8-6s8 2.8 8 6c0 5-8 11-8 11S4 13 4 8z"/>
                                        </svg>
                                        <svg x-show="favorited" xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span x-text="favorited ? 'Added' : 'Add'"></span>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $credits->links('pagination::tailwind') }}
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">No movies available in this genre.</p>
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

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
