<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // TMDB configuration
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');
        $imageUrl = config('services.tmdb.image_url');

        // Cache popular movies for 30 minutes
        $popularMovies = Cache::remember('popular_movies', 1800, function () use ($apiKey, $baseUrl) {
            try {
                $response = Http::timeout(5)->get("$baseUrl/movie/popular", [
                    'api_key' => $apiKey,
                    'language' => 'en-US',
                    'page' => 1,
                ]);

                if ($response->successful()) {
                    return $response->json()['results'] ?? [];
                }

                Log::warning('TMDB Movies API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            } catch (Exception $e) {
                Log::error('Error fetching popular movies', ['message' => $e->getMessage()]);
                return [];
            }
        });

        // Cache popular TV shows for 30 minutes
        $popularTvShows = Cache::remember('popular_tv', 1800, function () use ($apiKey, $baseUrl) {
            try {
                $response = Http::timeout(5)->get("$baseUrl/tv/popular", [
                    'api_key' => $apiKey,
                    'language' => 'en-US',
                    'page' => 1,
                ]);

                if ($response->successful()) {
                    return $response->json()['results'] ?? [];
                }

                Log::warning('TMDB TV API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            } catch (Exception $e) {
                Log::error('Error fetching popular TV shows', ['message' => $e->getMessage()]);
                return [];
            }
        });

        // Cache popular genres for 1 day
        $popularGenres = Cache::remember('popular_genres', 86400, function () use ($apiKey, $baseUrl) {
            try {
                $response = Http::timeout(5)->get("$baseUrl/genre/movie/list", [
                    'api_key' => $apiKey,
                    'language' => 'en-US',
                ]);

                if ($response->successful()) {
                    return $response->json()['genres'] ?? [];
                }

                Log::warning('TMDB Genre API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            } catch (Exception $e) {
                Log::error('Error fetching genres', ['message' => $e->getMessage()]);
                return [];
            }
        });

        // Cache popular actors for 1 hour
        $popularActors = Cache::remember('popular_actors', 3600, function () use ($apiKey, $baseUrl) {
            try {
                $response = Http::timeout(5)->get("$baseUrl/person/popular", [
                    'api_key' => $apiKey,
                    'language' => 'en-US',
                    'page' => 1,
                ]);

                if ($response->successful()) {
                    return $response->json()['results'] ?? [];
                }

                Log::warning('TMDB Actors API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            } catch (Exception $e) {
                Log::error('Error fetching popular actors', ['message' => $e->getMessage()]);
                return [];
            }
        });

        // Fetch user recommendations only if logged in
        $recommendedMovies = [];
        $recommendedTv = [];

        if (auth()->check()) {
            $favorites = auth()->user()->favorites()->select('type', 'item_id')->get();

            foreach ($favorites as $favorite) {
                $type = $favorite->type;
                $itemId = $favorite->item_id;

                // Cache each favorite's recommendations for 30 minutes
                $recs = Cache::remember("recommendations_{$type}_{$itemId}", 1800, function () use ($apiKey, $baseUrl, $type, $itemId) {
                    try {
                        $response = Http::timeout(5)->get("$baseUrl/$type/$itemId/recommendations", [
                            'api_key' => $apiKey,
                            'language' => 'en-US',
                            'page' => 1,
                        ]);

                        if ($response->successful()) {
                            return $response->json()['results'] ?? [];
                        }

                        Log::warning("TMDB recommendations API returned non-success for $type $itemId", [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                        return [];
                    } catch (Exception $e) {
                        Log::error("Error fetching recommendations for $type $itemId", ['message' => $e->getMessage()]);
                        return [];
                    }
                });

                foreach ($recs as $rec) {
                    $rec['media_type'] = $type;

                    if ($type === 'movie' && !collect($recommendedMovies)->contains(fn($r) => $r['id'] === $rec['id'])) {
                        $recommendedMovies[] = $rec;
                    } elseif ($type === 'tv' && !collect($recommendedTv)->contains(fn($r) => $r['id'] === $rec['id'])) {
                        $recommendedTv[] = $rec;
                    }
                }
            }

            $recommendedMovies = collect($recommendedMovies)->shuffle()->take(50)->values()->toArray();
            $recommendedTv = collect($recommendedTv)->shuffle()->take(50)->values()->toArray();
        }

        return view('dashboard', [
            'popularMovies' => $popularMovies,
            'popularTvShows' => $popularTvShows,
            'recommendedMovies' => $recommendedMovies,
            'recommendedTv' => $recommendedTv,
            'popularGenres' => $popularGenres,
            'popularActors' => $popularActors,
            'imageUrl' => $imageUrl,
        ]);
    }

/**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified Movie resources.
     */
    public function showMovie(string $id): View|Factory
    {
        // ✅ TMDB configuration
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');

        // Initialize empty movie array in case of failure
        $movie = [];

        try {
            // ✅ Fetch Movie Details with credits
            $response = Http::timeout(5) // 5 seconds timeout
            ->get("$baseUrl/movie/$id", [
                'api_key' => $apiKey,
                'append_to_response' => 'videos,credits',
                'language' => 'en-US',
            ]);

            if ($response->successful()) {
                $movie = $response->json();
            } else {
                // Log non-success status
                Log::warning('TMDB Movie API returned non-success status', [
                    'movie_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Exception $e) {
            // Log any unexpected error
            Log::error('Unexpected error fetching movie details', [
                'movie_id' => $id,
                'message' => $e->getMessage(),
            ]);
        }

        // If movie is empty, return 404 page or fallback
        if (empty($movie)) {
            abort(404, 'Movie not found or failed to load.');
        }

        // ✅ Return the movie-details view
        return view('details', compact('movie'));
    }

    /**
     * Display the specified TV resource.
     */
    public function showTv(string $id): View|Factory
    {
        // ✅ TMDB configuration
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');

        // Initialize empty tvShow array in case of failure
        $tvShow = [];

        try {
            // ✅ Fetch TV Show Details with credits and videos
            $response = Http::timeout(5) // 5 seconds timeout
            ->get("$baseUrl/tv/$id", [
                'api_key' => $apiKey,
                'append_to_response' => 'videos,credits', // Include trailers & cast
                'language' => 'en-US',
            ]);

            if ($response->successful()) {
                $tvShow = $response->json();
            } else {
                // Log non-success status
                Log::warning('TMDB TV API returned non-success status', [
                    'tv_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Exception $e) {
            // Log any unexpected error
            Log::error('Unexpected error fetching TV show details', [
                'tv_id' => $id,
                'message' => $e->getMessage(),
            ]);
        }

        // If TV show is empty, return 404 page or fallback
        if (empty($tvShow)) {
            abort(404, 'TV Show not found or failed to load.');
        }

        // ✅ Return the tvshow-details view
        return view('detailstv', compact('tvShow'));
    }


    /**
     * Display the specified Actor resource.
     */
    public function showActor(string $id): View|Factory
    {
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');

        $actor = [];
        $paginatedCredits = [];
        $popularGenres = [];

        try {
            // ✅ Fetch Actor Details with credits & images
            $response = Http::timeout(5)->get("$baseUrl/person/$id", [
                'api_key' => $apiKey,
                'append_to_response' => 'combined_credits,images',
                'language' => 'en-US',
            ]);

            if ($response->successful()) {
                $actor = $response->json();

                // ✅ Extract all credits (movies + tv shows)
                $combinedCredits = collect($actor['combined_credits']['cast'] ?? [])
                    ->sortByDesc(function ($credit) {
                        // sort by release/air date, newest first
                        return $credit['release_date'] ?? $credit['first_air_date'] ?? '';
                    })
                    ->values();

                // ✅ Pagination
                $perPage = 10; // you can increase/decrease this
                $currentPage = request()->get('page', 1);
                $pagedItems = $combinedCredits->forPage($currentPage, $perPage);

                $paginatedCredits = new \Illuminate\Pagination\LengthAwarePaginator(
                    $pagedItems,
                    $combinedCredits->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            } else {
                Log::warning('TMDB Actor API returned non-success status', [
                    'actor_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error fetching actor details', [
                'actor_id' => $id,
                'message' => $e->getMessage(),
            ]);
        }

        try {
            // Fetch popular genres
            $genreResponse = Http::timeout(5)
                ->get("$baseUrl/genre/movie/list", [
                    'api_key' => $apiKey,
                    'language' => 'en-US',
                ]);

            if ($genreResponse->successful()) {
                $popularGenres = $genreResponse->json()['genres'] ?? [];
            } else {
                Log::warning('TMDB Genre API returned non-success status', [
                    'status' => $genreResponse->status(),
                    'body' => $genreResponse->body(),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Unexpected error fetching genres', ['message' => $e->getMessage()]);
        }

        if (empty($actor)) {
            abort(404, 'Actor not found or failed to load.');
        }

        // ✅ Return the actor-details view with paginated credits
        return view('detailsactor', [
            'actor' => $actor,
            'credits' => $paginatedCredits,
            'popularGenres' => $popularGenres
        ]);
    }


    /**
     * Display the specified Genre resource.
     */
    public function showGenre(string $id): View|Factory
    {
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');

        $movies = [];
        $genreName = 'Genre'; // default fallback
        $page = request()->get('page', 1); // current page from query params

        try {
            // ✅ Fetch movies by genre with pagination
            $response = Http::timeout(5)->get("$baseUrl/discover/movie", [
                'api_key' => $apiKey,
                'with_genres' => $id,
                'language' => 'en-US',
                'page' => $page,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // TMDB returns: results, total_pages, total_results
                $movies = new LengthAwarePaginator(
                    $data['results'] ?? [],
                    $data['total_results'] ?? 0,
                    20, // TMDB default page size
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            } else {
                Log::warning('TMDB Genre API returned non-success status', [
                    'genre_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            // ✅ Fetch genres list to get the genre name
            $genresResponse = Http::timeout(5)->get("$baseUrl/genre/movie/list", [
                'api_key' => $apiKey,
                'language' => 'en-US',
            ]);

            if ($genresResponse->successful()) {
                $genresData = $genresResponse->json();
                $genre = collect($genresData['genres'] ?? [])->firstWhere('id', (int)$id);
                if ($genre) {
                    $genreName = $genre['name'];
                }
            }

        } catch (Exception $e) {
            Log::error('Unexpected error fetching movies or genre name', [
                'genre_id' => $id,
                'message' => $e->getMessage(),
            ]);
        }

        if (empty($movies)) {
            abort(404, 'Movies not found for this genre.');
        }

        // ✅ Return genre-movies view with movies and genre name
        return view('genremovies', compact('movies', 'id', 'genreName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
