<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MovieApp</title>

    <!-- ✅ Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com" defer></script>

    <!-- ✅ Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- ✅ Preload Fonts -->
    <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" onload="this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>
    </noscript>

    <!-- Alpine.js -->
    <script defer src="//unpkg.com/alpinejs"></script>

    <!-- Optional Animation -->
    <style>
        .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col
             bg-gradient-to-br from-white via-gray-100 to-gray-200
             dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a]
             text-black dark:text-white overflow-x-hidden">

<!-- Loading Screen -->
<div id="loadingScreen"
     class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-black transition-opacity">
    <div class="flex flex-col items-center">
        <div class="w-16 h-16 border-4 border-t-[#e50914] border-gray-300 rounded-full animate-spin mb-4"></div>
        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Loading MovieApp...</span>
    </div>
</div>

<!-- Welcome Banner Modal -->
<div x-data="{ showBanner: true }"
     x-init="setTimeout(() => showBanner = false, 5000)"
     x-show="showBanner"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 scale-90"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-500"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-90"
     class="fixed inset-0 z-50 flex items-center justify-center">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <div class="relative bg-[#e50914] text-white rounded-xl shadow-xl p-8 max-w-lg w-[90%] flex flex-col items-center gap-4">
        <h2 class="text-2xl font-bold text-center">Welcome to MovieApp!</h2>
        <p class="text-center">Login or Sign up for the Full Experience</p>
        <button @click="showBanner = false"
                class="mt-2 px-6 py-2 bg-white text-[#e50914] rounded-lg font-semibold shadow hover:bg-gray-100 transition">
            ✕ Close
        </button>
    </div>
</div>

<div :class="{ 'filter blur-sm pointer-events-none': showBanner }" class="transition-all duration-300">
    <div id="appContent" class="opacity-0 transition-opacity duration-500">

        <!-- Navbar -->
        <header class="w-full sticky top-0 z-50 bg-white/90 dark:bg-black/80 backdrop-blur-md shadow">
            <div class="flex items-center justify-between px-6 py-4">
                <a href="{{ url('/') }}"
                   class="text-2xl font-extrabold tracking-tight text-black dark:text-white hover:text-[#e50914]">
                    Movie<span class="text-[#e50914]">App</span>
                </a>

                @if(Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-5 py-2 bg-[#e50914] text-white rounded-lg text-sm font-semibold shadow hover:bg-[#b20710] transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-5 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:text-[#e50914] transition">
                                Log in
                            </a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-5 py-2 bg-[#e50914] text-white rounded-lg text-sm font-semibold shadow hover:bg-[#b20710] transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <!-- Hero Slider -->
        <section class="w-full h-screen relative">
            <div class="swiper mySwiper h-full w-full">
                <div class="swiper-wrapper h-full">
                    @foreach(collect($popularMovies)->take(5)->merge(collect($popularTvShows)->take(5)) as $item)
                        <div class="swiper-slide relative w-full h-full">
                            <img loading="eager"
                                 src="https://image.tmdb.org/t/p/original{{ $item['backdrop_path'] ?? $item['poster_path'] }}"
                                 alt="{{ $item['title'] ?? $item['name'] }}"
                                 class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-white/50 via-white/20 to-transparent dark:from-black dark:via-black/40 dark:to-transparent flex items-end">
                                <div class="p-8 md:p-16 max-w-3xl -translate-y-20">
                                    <h2 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg text-black dark:text-white">
                                        {{ $item['title'] ?? $item['name'] }}
                                    </h2>
                                    <p class="text-xl text-gray-800 dark:text-gray-300 mb-4 drop-shadow">
                                        ⭐ {{ $item['vote_average'] }}
                                    </p>
                                    <a href="{{ isset($item['title']) ? 'https://www.themoviedb.org/movie/'.$item['id'] : 'https://www.themoviedb.org/tv/'.$item['id'] }}"
                                       target="_blank"
                                       class="inline-block px-6 py-3 bg-[#e50914] text-white rounded-lg text-base font-semibold shadow hover:bg-[#b20710] transition">
                                        Watch Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <main class="flex-1 w-full max-w-[1600px] mx-auto px-10 py-10">
            <div x-data="{ showModal: false, selected: {} }">

                <!-- Popular Movies Grid -->
                <h1 class="text-3xl font-bold mb-6 text-center">Popular <span class="text-[#e50914]">Movies</span></h1>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6 mb-14">
                    @foreach($popularMovies as $movie)
                        <div @click="showModal = true; selected = {{ json_encode($movie) }}"
                             class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                            <img loading="lazy" src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/70 dark:bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                                <h2 class="text-lg font-semibold truncate text-white dark:text-white">{{ $movie['title'] }}</h2>
                                <p class="text-sm text-white dark:text-white">⭐ {{ $movie['vote_average'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Popular TV Shows Grid -->
                <h1 class="text-3xl font-bold mb-6 text-center">Popular <span class="text-[#e50914]">TV Shows</span></h1>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6">
                    @foreach($popularTvShows as $show)
                        <div @click="showModal = true; selected = {{ json_encode($show) }}"
                             class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                            <img loading="lazy" src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                                <h2 class="text-lg font-semibold truncate">{{ $show['name'] }}</h2>
                                <p class="text-sm text-gray-300">⭐ {{ $show['vote_average'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modal -->
                <div x-show="showModal" x-transition.opacity
                     class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4" style="display: none;">
                    <div x-transition.scale @click.away="showModal = false"
                         class="bg-gray-900 rounded-xl overflow-hidden max-w-3xl w-full shadow-2xl animate-fade-in-up">
                        <img :src="'https://image.tmdb.org/t/p/original'+(selected.backdrop_path ?? selected.poster_path)"
                             :alt="selected.title || selected.name"
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-white mb-2" x-text="selected.title || selected.name"></h2>
                            <p class="text-gray-300 mb-4" x-text="selected.overview || 'No description available.'"></p>
                            <p class="text-gray-300 font-semibold mb-4">⭐ <span x-text="selected.vote_average"></span></p>
                            <button @click="showModal = false"
                                    class="px-5 py-2 bg-[#e50914] text-white rounded-lg font-semibold hover:bg-[#b20710] transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full bg-black/90 backdrop-blur-md py-8 mt-10 border-t border-gray-700/50 text-gray-400">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm md:text-base">© {{ date('Y') }} <span class="font-semibold text-white">MovieApp</span>. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <!-- Social Icons (SVG) -->
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Swiper JS -->
<script defer src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script defer>
    window.addEventListener('load', () => {
        const loader = document.getElementById('loadingScreen');
        const content = document.getElementById('appContent');
        loader.classList.add('opacity-0');
        setTimeout(() => loader.style.display = 'none', 500);
        content.classList.remove('opacity-0');

        const swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true },
            effect: "slide",
        });
    });
</script>

</body>
</html>
