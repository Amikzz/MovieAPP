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
        [x-cloak] {
            display: none !important;
        }
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
            <!-- Navbar Genres Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <a href="#"
                   @mouseenter="open = true"
                   @mouseleave="open = false"
                   class="text-black dark:text-white font-medium hover:text-[#e50914] transition cursor-pointer">
                    Genres
                </a>

                <!-- Dropdown Menu -->
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
                        <a href="#"
                           class="block px-3 py-2 rounded hover:bg-[#e50914] hover:text-white text-gray-700 dark:text-gray-200 transition font-medium whitespace-nowrap min-w-max">
                            {{ $genre['name'] }}
                        </a>
                    @endforeach
                </div>

            </div>
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
                         :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
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
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5.121 17.804A7.968 7.968 0 0112 15c1.657 0 3.168.504 4.379 1.358m1.242 1.446A8.003 8.003 0 015.121 17.804m0 0A7.964 7.964 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8a7.964 7.964 0 01-1.121 5.804z"/>
                        </svg>
                        My Profile
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-800 transition">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3z"/>
                        </svg>
                        Dashboard
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

<main class="flex-1 w-full max-w-[1600px] mx-auto px-10 py-10">

    <!-- Recommended Movies Section -->
    @if(collect($recommendedMovies)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Recommended <span class="text-[#e50914]">Movies</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable Items Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($recommendedMovies as $item)
                        @php
                            $type = 'movie'; // always movie in this loop
                            $itemId = $item['id'];
                            $isFavorited = auth()->check()
                                ? auth()->user()->favorites()->where('type', $type)->where('item_id', $itemId)->exists()
                                : false;
                        @endphp

                        <div
                            class="flex-shrink-0 w-48 md:w-56 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                            <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}"
                                 alt="{{ $item['title'] ?? $item['name'] }}"
                                 class="w-full h-72 md:h-80 object-cover rounded-lg">

                            <!-- Overlay with Title & Buttons -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-lg">
                                <h2 class="text-md md:text-lg font-semibold truncate text-white">{{ $item['title'] ?? $item['name'] }}</h2>
                                <p class="text-sm text-white mb-2">⭐ {{ $item['vote_average'] }}</p>

                                <div class="flex gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('movies.show', ['id' => $itemId]) }}"
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

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif

    <!-- Recommended TV Shows Section -->
    @if(collect($recommendedTv)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Recommended <span class="text-[#e50914]">TV Shows</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable Items Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($recommendedTv as $item)
                        @php
                            $type = 'tv'; // always movie in this loop
                            $itemId = $item['id'];
                            $isFavorited = auth()->check()
                                ? auth()->user()->favorites()->where('type', $type)->where('item_id', $itemId)->exists()
                                : false;
                        @endphp

                        <div
                            class="flex-shrink-0 w-48 md:w-56 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                            <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}"
                                 alt="{{ $item['title'] ?? $item['name'] }}"
                                 class="w-full h-72 md:h-80 object-cover rounded-lg">

                            <!-- Overlay with Title & Buttons -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-lg">
                                <h2 class="text-md md:text-lg font-semibold truncate text-white">{{ $item['title'] ?? $item['name'] }}</h2>
                                <p class="text-sm text-white mb-2">⭐ {{ $item['vote_average'] }}</p>

                                <div class="flex gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('movies.show', ['id' => $itemId]) }}"
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

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif

    <!-- Popular Movies Section -->
    @if(collect($popularMovies)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Popular <span class="text-[#e50914]">Movies</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable Movies Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($popularMovies as $movie)
                        @php
                            $type = 'movie';
                            $itemId = $movie['id'];
                            $isFavorited = auth()->check()
                                            ? auth()->user()->favorites()->where('type', $type)->where('item_id', $itemId)->exists()
                                            : false;
                        @endphp

                        <div
                            class="flex-shrink-0 w-48 md:w-56 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-72 md:h-80 object-cover rounded-lg">

                            <!-- Overlay with Title & Buttons -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-lg">
                                <h2 class="text-md md:text-lg font-semibold truncate text-white">{{ $movie['title'] }}</h2>
                                <p class="text-sm text-white mb-2">⭐ {{ $movie['vote_average'] }}</p>

                                <div class="flex gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('movies.show', ['id' => $movie['id']]) }}"
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

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            /* Hide scrollbar for all browsers */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif

    <!-- Popular TV Shows Section -->
    @if(collect($popularTvShows)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Popular <span class="text-[#e50914]">TV Shows</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable TV Shows Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($popularTvShows as $show)
                        @php
                            $type = 'tv';
                            $itemId = $show['id'];
                            $isFavorited = auth()->check()
                                ? auth()->user()->favorites()->where('type', $type)->where('item_id', $itemId)->exists()
                                : false;
                        @endphp

                        <div class="flex-shrink-0 w-48 md:w-56 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                            <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}"
                                 class="w-full h-72 md:h-80 object-cover rounded-lg">

                            <!-- Overlay with Title & Buttons -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-lg">
                                <h2 class="text-md md:text-lg font-semibold truncate text-white">{{ $show['name'] }}</h2>
                                <p class="text-sm text-white mb-2">⭐ {{ $show['vote_average'] }}</p>

                                <div class="flex gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('tv.show', ['id' => $itemId]) }}"
                                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow hover:scale-105 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m6 0l-3-3m3 3l-3 3" />
                                        </svg>
                                        <span>View</span>
                                    </a>

                                    <!-- Favorite Button -->
                                    @auth
                                        <button
                                            x-data="{ favorited: @json($isFavorited) }"
                                            x-on:click="
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
                            "
                                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-gradient-to-r from-[#e50914] to-[#ff3d5f] text-white font-semibold shadow hover:scale-105 transition"
                                        >
                                            <svg x-show="!favorited" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8c0-3.2 4-6 8-6s8 2.8 8 6c0 5-8 11-8 11S4 13 4 8z" />
                                            </svg>
                                            <svg x-show="favorited" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span x-text="favorited ? 'Added' : 'Add'"></span>
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            /* Hide scrollbar for all browsers */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif

    <!-- Popular Actors Section -->
    @if(collect($popularActors)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Popular <span class="text-[#e50914]">Actors</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable Actors Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($popularActors as $actor)
                        <div
                            class="flex-shrink-0 w-48 md:w-56 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                            <a href="">
                                <img src="https://image.tmdb.org/t/p/w500{{ $actor['profile_path'] ?? '' }}"
                                     alt="{{ $actor['name'] }}"
                                     class="w-full h-72 md:h-80 object-cover rounded-lg">
                            </a>

                            <!-- Overlay with Name -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-lg">
                                <h2 class="text-md md:text-lg font-semibold truncate text-white">{{ $actor['name'] }}</h2>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            /* Hide scrollbar for all browsers */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif

    <!-- Popular Genres Section -->
    @if(collect($popularGenres)->isNotEmpty())
        <section class="mb-14 relative">
            <h1 class="text-3xl font-bold mb-6 text-center">
                Popular <span class="text-[#e50914]">Genres</span>
            </h1>

            <div x-data class="relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Scrollable Genres Row -->
                <div
                    x-ref="scrollContainer"
                    class="flex gap-4 px-4 overflow-x-auto overflow-y-hidden no-scrollbar py-2"
                    style="scroll-behavior: smooth;"
                >
                    @foreach($popularGenres as $genre)
                        <div
                            class="flex-shrink-0 w-40 md:w-48 relative group rounded-lg shadow-lg hover:scale-105 transform transition duration-300 bg-gradient-to-r from-gray-700 to-gray-900 text-white flex items-center justify-center h-24 md:h-32 cursor-pointer">
                            <span class="text-lg md:text-xl font-semibold">{{ $genre['name'] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button
                    @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-2 rounded-full z-10 shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        <style>
            /* Hide scrollbar for all browsers */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif
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
                    <path
                        d="M22.46 6c-.77.35-1.6.58-2.46.69a4.28 4.28 0 001.88-2.37 8.5 8.5 0 01-2.7 1.03 4.27 4.27 0 00-7.28 3.9A12.1 12.1 0 013 4.8a4.27 4.27 0 001.32 5.7 4.2 4.2 0 01-1.94-.54v.05a4.27 4.27 0 003.42 4.19 4.3 4.3 0 01-1.93.07 4.27 4.27 0 003.99 2.96A8.57 8.57 0 012 19.54a12.06 12.06 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19-.01-.37-.02-.56A8.64 8.64 0 0024 5.1a8.35 8.35 0 01-2.54.7z"/>
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#e50914] transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 4.41 2.86 8.16 6.84 9.49v-6.72h-2.06V12h2.06V9.8c0-2.04 1.21-3.16 3.06-3.16.89 0 1.82.16 1.82.16v2h-1.03c-1.01 0-1.32.63-1.32 1.27V12h2.25l-.36 2.77h-1.89v6.72a9.953 9.953 0 006.84-9.49c0-5.5-4.46-9.96-9.96-9.96z"/>
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#e50914] transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M22.22 5.72c-.77.34-1.6.58-2.46.69a4.27 4.27 0 001.88-2.37 8.5 8.5 0 01-2.7 1.03 4.27 4.27 0 00-7.28 3.9A12.1 12.1 0 013 4.8a4.27 4.27 0 001.32 5.7 4.2 4.2 0 01-1.94-.54v.05a4.27 4.27 0 003.42 4.19 4.3 4.3 0 01-1.93.07 4.27 4.27 0 003.99 2.96A8.57 8.57 0 012 19.54a12.06 12.06 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19-.01-.37-.02-.56A8.64 8.64 0 0024 5.1a8.35 8.35 0 01-2.54.7z"/>
                </svg>
            </a>
        </div>
    </div>
</footer>

</body>
</html>
