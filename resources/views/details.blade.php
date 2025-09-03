<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $movie['title'] ?? $movie['name'] }} - MovieApp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', // ✅ follows system preference
        }
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

        .cast-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .cast-scroll::-webkit-scrollbar-thumb {
            background-color: #e50914;
            border-radius: 10px;
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

<body class="min-h-screen
             bg-gradient-to-br from-white via-gray-100 to-gray-200 text-black
             dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a] dark:text-white">

<!-- Loader -->
<div id="loader" class="fixed inset-0 flex items-center justify-center
    bg-white dark:bg-black z-[100] transition-opacity duration-500">
    <div class="loader"></div>
</div>

<main id="pageContent" class="w-full relative opacity-0 translate-y-4 transition-all duration-700">

    <!-- Back Button -->
    <div class="max-w-7xl mx-auto px-8 py-6">
        <button onclick="window.history.back()"
                class="px-4 py-2 bg-[#e50914] text-white rounded-lg shadow hover:bg-[#b20710] transition">
            ← Back
        </button>
    </div>

    <!-- Movie Banner -->
    <div class="max-w-7xl mx-auto px-8">
        <div class="relative w-full rounded-3xl shadow-xl overflow-hidden animate-fadeIn">
            <img src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] ?? $movie['poster_path'] }}"
                 alt="{{ $movie['title'] ?? $movie['name'] }}"
                 class="w-full h-96 md:h-[600px] object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent flex items-end p-6">
                <div class="max-w-4xl">
                    <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-lg">
                        {{ $movie['title'] ?? $movie['name'] }}
                    </h1>
                    <p class="text-gray-200 mt-2 md:text-lg drop-shadow">
                        {{ $movie['overview'] ?? 'No description available.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Favorites Button -->
    @auth
        @php
            $type = 'movie'; // since this is on a movie details page
            $itemId = $movie['id'];

            $isFavorited = auth()->user()->favorites()
                ->where('type', $type)
                ->where('item_id', $itemId)
                ->exists();
        @endphp

        <div class="max-w-7xl mx-auto px-8 mt-6 animate-fadeUp delay-400">
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
                .catch(err => {
                    console.error(err);
                    alert('Failed to add/remove favorite');
                })
            "
                class="flex items-center gap-3 px-6 py-3 rounded-full shadow-lg font-semibold
               text-white transition transform hover:scale-105
               bg-gradient-to-r from-[#e50914] to-[#ff3d5f]
               dark:from-[#b20710] dark:to-[#ff1f4f] cursor-pointer"
            >
                <!-- Heart icon -->
                <svg x-show="!favorited" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 8c0-3.2 4-6 8-6s8 2.8 8 6c0 5-8 11-8 11S4 13 4 8z"/>
                </svg>
                <svg x-show="favorited" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>

                <!-- Button text -->
                <span x-text="favorited ? 'Added to Favorites' : 'Add to Favorites'"></span>
            </button>
        </div>
    @endauth

    <!-- Movie Stats -->
    <div
        class="max-w-7xl mx-auto px-8 py-8 mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 animate-fadeUp delay-200">
        @php
            $stats = [
                ['icon' => 'calendar', 'label' => 'Release Date', 'value' => $movie['release_date'] ?? 'N/A'],
                ['icon' => 'tag', 'label' => 'Genre', 'value' => !empty($movie['genres']) ? implode(', ', array_column($movie['genres'], 'name')) : 'N/A'],
                ['icon' => 'clock', 'label' => 'Runtime', 'value' => ($movie['runtime'] ?? 'N/A') . ' min'],
                ['icon' => 'star', 'label' => 'Rating', 'value' => $movie['vote_average'] ?? 'N/A'],
                ['icon' => 'globe', 'label' => 'Language', 'value' => strtoupper($movie['original_language'] ?? 'N/A')],
                ['icon' => 'film', 'label' => 'Status', 'value' => $movie['status'] ?? 'N/A'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="flex items-center gap-3
            bg-white dark:bg-black/90
            rounded-xl p-5 shadow
            hover:scale-105 transition
            border border-gray-200 dark:border-gray-800">
                <i data-feather="{{ $stat['icon'] }}" class="text-[#e50914]"></i>
                <div>
                    <h3 class="font-semibold text-gray-600 dark:text-gray-300">{{ $stat['label'] }}</h3>
                    <p class="text-gray-900 dark:text-gray-200">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cast -->
    @if(!empty($movie['credits']['cast']))
        <div class="max-w-7xl mx-auto px-8 py-6 mt-10 animate-fadeUp delay-300">
            <h2 class="text-2xl font-semibold mb-4">Top Billed Cast</h2>
            <div class="flex gap-4 overflow-x-auto cast-scroll pb-2">
                @foreach(array_slice($movie['credits']['cast'], 0, 10) as $cast)
                    <div class="w-28 text-center flex-shrink-0">
                        <img src="https://image.tmdb.org/t/p/w200{{ $cast['profile_path'] ?? '' }}"
                             alt="{{ $cast['name'] }}"
                             class="rounded-xl w-full h-36 object-cover mb-2 shadow hover:scale-105 transform transition">
                        <p class="text-sm font-semibold">{{ $cast['name'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cast['character'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Trailer -->
    @if(!empty($movie['videos']['results']))
        @php
            $trailer = collect($movie['videos']['results'])
                        ->firstWhere('type', 'Trailer')
                        ?? collect($movie['videos']['results'])->firstWhere('type', 'Teaser')
                        ?? $movie['videos']['results'][0] ?? null;

            $embedUrl = null;

            if ($trailer) {
                $embedUrl = match (strtolower($trailer['site'])) {
                    'youtube' => "https://www.youtube.com/embed/" . $trailer['key'],
                    'vimeo' => "https://player.vimeo.com/video/" . $trailer['key'],
                    default => "https://www.themoviedb.org/video/play?key=" . $trailer['key'],
                };
            }
        @endphp

        @if($embedUrl)
            <div class="max-w-7xl mx-auto px-8 py-6 mt-10 animate-fadeUp delay-500">
                <h2 class="text-2xl font-semibold mb-4">Trailer</h2>
                <div class="relative aspect-video rounded-xl overflow-hidden shadow-lg">
                    <iframe class="w-full h-full"
                            src="{{ $embedUrl }}"
                            title="Movie Trailer"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif
    @endif


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
