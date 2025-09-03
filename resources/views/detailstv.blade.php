{{-- resources/views/tvshow-details.blade.php --}}
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tvShow['name'] }} - MovieApp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = {darkMode: 'media'};</script>

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
<body
    class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-gray-200 text-black dark:bg-gradient-to-br dark:from-[#0f0f0f] dark:via-[#1a1a1a] dark:to-[#0a0a0a] dark:text-white">

<!-- Loader -->
<div id="loader"
     class="fixed inset-0 flex items-center justify-center bg-white dark:bg-black z-[100] transition-opacity duration-500">
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

    <!-- TV Show Banner -->
    <div class="max-w-7xl mx-auto px-8">
        <div class="relative w-full rounded-3xl shadow-xl overflow-hidden animate-fadeIn">
            <img src="https://image.tmdb.org/t/p/original{{ $tvShow['backdrop_path'] ?? $tvShow['poster_path'] }}"
                 alt="{{ $tvShow['name'] }}"
                 class="w-full h-96 md:h-[600px] object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent flex items-end p-6">
                <div class="max-w-4xl">
                    <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-lg">
                        {{ $tvShow['name'] }}
                    </h1>
                    <p class="text-gray-200 mt-2 md:text-lg drop-shadow">
                        {{ $tvShow['overview'] ?? 'No description available.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Favorites Button -->
    @auth
        @php
            $type = 'tv';
            $itemId = $tvShow['id'];

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
                <!-- Heart icon (not favorited) -->
                <svg x-show="!favorited" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 8c0-3.2 4-6 8-6s8 2.8 8 6c0 5-8 11-8 11S4 13 4 8z"/>
                </svg>

                <!-- Checkmark icon (favorited) -->
                <svg x-show="favorited" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>

                <!-- Button text -->
                <span x-text="favorited ? 'Added to Favorites' : 'Add to Favorites'"></span>
            </button>
        </div>
    @endauth

    <!-- TV Show Stats -->
    <div
        class="max-w-7xl mx-auto px-8 py-8 mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 animate-fadeUp delay-200">
        @php
            $stats = [
                ['icon' => 'calendar', 'label' => 'First Air Date', 'value' => $tvShow['first_air_date'] ?? 'N/A'],
                ['icon' => 'calendar', 'label' => 'Last Air Date', 'value' => $tvShow['last_air_date'] ?? 'N/A'],
                ['icon' => 'tag', 'label' => 'Genres', 'value' => !empty($tvShow['genres']) ? implode(', ', array_column($tvShow['genres'], 'name')) : 'N/A'],
                ['icon' => 'star', 'label' => 'Rating', 'value' => $tvShow['vote_average'] ?? 'N/A'],
                ['icon' => 'film', 'label' => 'Status', 'value' => $tvShow['status'] ?? 'N/A'],
                ['icon' => 'users', 'label' => 'Created By', 'value' => !empty($tvShow['created_by']) ? implode(', ', array_column($tvShow['created_by'], 'name')) : 'N/A'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div
                class="flex items-center gap-3 bg-white dark:bg-black/90 rounded-xl p-5 shadow hover:scale-105 transition border border-gray-200 dark:border-gray-800">
                <i data-feather="{{ $stat['icon'] }}" class="text-[#e50914]"></i>
                <div>
                    <h3 class="font-semibold text-gray-600 dark:text-gray-300">{{ $stat['label'] }}</h3>
                    <p class="text-gray-900 dark:text-gray-200">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cast -->
    @if(!empty($tvShow['credits']['cast']))
        <div class="max-w-7xl mx-auto px-8 py-6 mt-10 animate-fadeUp delay-300">
            <h2 class="text-2xl font-semibold mb-4">Top Billed Cast</h2>
            <div class="flex gap-4 overflow-x-auto cast-scroll pb-2">
                @foreach(array_slice($tvShow['credits']['cast'], 0, 10) as $cast)
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

    <!-- Seasons -->
    @if(!empty($tvShow['seasons']))
        <div class="max-w-7xl mx-auto px-8 py-6 mt-10 animate-fadeUp delay-400">
            <h2 class="text-2xl font-semibold mb-4">Seasons</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($tvShow['seasons'] as $season)
                    <div class="relative group rounded-xl overflow-hidden shadow-lg hover:scale-105 transform transition duration-300 cursor-pointer">
                        <!-- Poster or Placeholder -->
                        @if(!empty($season['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w500{{ $season['poster_path'] }}"
                                 alt="{{ $season['name'] }}"
                                 class="w-full h-72 object-cover rounded-xl">
                        @else
                            <div class="w-full h-72 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a2 2 0 002 2h3l3 3 4-4 5 5V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span class="text-gray-500 mt-2 text-sm">No Image</span>
                            </div>
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-3 rounded-xl">
                            <h3 class="text-md md:text-lg font-semibold truncate text-white">
                                {{ $season['name'] }}
                            </h3>
                            <p class="text-sm text-white mb-1">
                                Air Date: {{ $season['air_date'] ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-white">
                                Episodes: {{ $season['episode_count'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Trailer -->
    @if(!empty($tvShow['videos']['results']))
        @php
            $trailer = collect($tvShow['videos']['results'])->firstWhere('type', 'Trailer')
                        ?? collect($tvShow['videos']['results'])->firstWhere('type', 'Teaser')
                        ?? $tvShow['videos']['results'][0] ?? null;

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
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" title="TV Show Trailer"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        @endif
    @endif

</main>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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

</body>
</html>
