<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MovieApp</title>

    <!-- ✅ Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- ✅ Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Optional Animation for Modal -->
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col
             bg-gradient-to-br from-white via-gray-100 to-gray-200
             dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a]
             text-black dark:text-white">

<!-- Navbar -->
<header class="w-full sticky top-0 z-50
               bg-white/90 dark:bg-black/80 backdrop-blur-md shadow">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Logo -->
        <a href="{{ url('/') }}"
           class="text-2xl font-extrabold tracking-tight transition text-center
                  text-black dark:text-white hover:text-[#e50914]">
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
                       class="px-5 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200
                              border border-gray-300 dark:border-gray-600 rounded-lg
                              hover:text-[#e50914] transition">
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
</header>

<!-- Hero Section -->
<section class="w-full h-screen relative">
    <div class="swiper mySwiper h-full w-full">
        <div class="swiper-wrapper h-full">
            @foreach(collect($popularMovies)->take(5)->merge(collect($popularTvShows)->take(5)) as $item)
                <div class="swiper-slide relative w-full h-full">
                    <!-- Background Image -->
                    <img src="https://image.tmdb.org/t/p/original{{ $item['backdrop_path'] ?? $item['poster_path'] }}"
                         alt="{{ $item['title'] ?? $item['name'] }}"
                         class="absolute inset-0 w-full h-full object-cover">

                    <!-- Overlay + Content -->
                    <div class="absolute inset-0
                                bg-gradient-to-t
                                from-white/50 via-white/20 to-transparent
                                dark:from-black dark:via-black/40 dark:to-transparent
                                flex items-end">
                        <div class="p-8 md:p-16 max-w-3xl -translate-y-20">
                            <h2 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg
                                       text-black dark:text-white">
                                {{ $item['title'] ?? $item['name'] }}
                            </h2>
                            <p class="text-xl text-gray-800 dark:text-gray-300 mb-4 drop-shadow">
                                ⭐ {{ $item['vote_average'] }}
                            </p>
                            <a href="{{ isset($item['title']) ? 'https://www.themoviedb.org/movie/' . $item['id'] : 'https://www.themoviedb.org/tv/' . $item['id'] }}"
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

    <!-- Alpine.js State for Modal -->
    <div x-data="{ showModal: false, selected: {} }">

        <!-- Popular Movies -->
        <h1 class="text-3xl font-bold mb-6 text-center">
            Popular <span class="text-[#e50914]">Movies</span>
        </h1>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6 mb-14">
            @foreach($popularMovies as $movie)
                <div @click="showModal = true; selected = {{ json_encode($movie) }}"
                     class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                         alt="{{ $movie['title'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0
                                bg-black/70 dark:bg-black/70
                                group-hover:bg-black/50 dark:group-hover:bg-black/50
                                opacity-0 group-hover:opacity-100
                                transition flex flex-col justify-end p-4">

                        <h2 class="text-lg font-semibold truncate
                                    text-white dark:text-white">
                            {{ $movie['title'] }}
                        </h2>
                        <p class="text-sm text-white dark:text-white">
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
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-6">
            @foreach($popularTvShows as $show)
                <div @click="showModal = true; selected = {{ json_encode($show) }}"
                     class="group relative rounded-lg overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                    <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                         alt="{{ $show['name'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                        <h2 class="text-lg font-semibold truncate">{{ $show['name'] }}</h2>
                        <p class="text-sm text-gray-300">⭐ {{ $show['vote_average'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div x-show="showModal"
             x-transition.opacity
             class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4"
             style="display: none;">
            <div x-transition.scale
                 @click.away="showModal = false"
                 class="bg-gray-900 rounded-xl overflow-hidden max-w-3xl w-full shadow-2xl animate-fade-in-up">

                <!-- Banner Image -->
                <img :src="'https://image.tmdb.org/t/p/original' + (selected.backdrop_path ?? selected.poster_path)"
                     :alt="selected.title || selected.name"
                     class="w-full h-64 object-cover">

                <!-- Content -->
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

<!-- ✅ Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        effect: "slide",
    });
</script>

</body>
</html>
