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

    <!-- Actor Profile Section -->
    <div class="max-w-7xl mx-auto px-8 mt-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <!-- Details -->
        <div class="space-y-4 animate-fadeUp delay-200">
            <h1 class="text-4xl md:text-5xl font-bold">{{ $actor['name'] }}</h1>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $actor['biography'] ?: 'No biography available.' }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <div class="flex items-center gap-2">
                    <i data-feather="calendar" class="text-[#e50914]"></i>
                    <span><strong>Birthday:</strong> {{ $actor['birthday'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-feather="map-pin" class="text-[#e50914]"></i>
                    <span><strong>Place of Birth:</strong> {{ $actor['place_of_birth'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-feather="star" class="text-[#e50914]"></i>
                    <span><strong>Popularity:</strong> {{ $actor['popularity'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-feather="film" class="text-[#e50914]"></i>
                    <span><strong>Known For:</strong> {{ $actor['known_for_department'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Picture -->
        <div class="animate-fadeIn delay-300">
            <img src="https://image.tmdb.org/t/p/w500{{ $actor['profile_path'] ?? '' }}"
                 alt="{{ $actor['name'] }}"
                 class="w-full rounded-3xl shadow-xl object-cover">
        </div>
    </div>

    <!-- Movies & TV Appearances -->
    <div class="max-w-7xl mx-auto px-8 py-8 mt-12">
        <h2 class="text-2xl font-semibold mb-6">Filmography</h2>

        @php
            $combinedCredits = $actor['combined_credits']['cast'] ?? [];
        @endphp

        @if(!empty($combinedCredits))
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($combinedCredits as $credit)
                    <div class="bg-white dark:bg-black/90 rounded-xl shadow overflow-hidden hover:scale-105 transition">
                        <img src="https://image.tmdb.org/t/p/w300{{ $credit['poster_path'] ?? '' }}"
                             alt="{{ $credit['title'] ?? $credit['name'] }}"
                             class="w-full h-60 object-cover">
                        <div class="p-3">
                            <h3 class="font-semibold text-sm truncate">
                                {{ $credit['title'] ?? $credit['name'] }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ ucfirst($credit['media_type']) }} • {{ $credit['character'] ?? 'N/A' }}
                            </p>
                            <p class="text-xs mt-1 text-gray-600 dark:text-gray-300">
                                {{ $credit['release_date'] ?? $credit['first_air_date'] ?? 'Unknown Date' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">No filmography available.</p>
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
