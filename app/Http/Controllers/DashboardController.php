<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Factory
    {
        $apiKey   = config('services.tmdb.api_key');
        $baseUrl  = config('services.tmdb.base_url');
        $imageUrl = config('services.tmdb.image_url');

        $popularMovies = [];
        $popularTvShows = [];
        $recommendedMovies = [];

        try {
            // Fetch popular movies
            $moviesResponse = Http::timeout(5)
                ->get("$baseUrl/movie/popular", [
                    'api_key'  => $apiKey,
                    'language' => 'en-US',
                    'page'     => 1,
                ]);

            if ($moviesResponse->successful()) {
                $popularMovies = $moviesResponse->json()['results'] ?? [];
            } else {
                Log::warning('TMDB Movies API returned non-success status', [
                    'status' => $moviesResponse->status(),
                    'body'   => $moviesResponse->body(),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Unexpected error fetching movies', ['message' => $e->getMessage()]);
        }

        try {
            // Fetch popular TV shows
            $tvResponse = Http::timeout(5)
                ->get("$baseUrl/tv/popular", [
                    'api_key'  => $apiKey,
                    'language' => 'en-US',
                    'page'     => 1,
                ]);

            if ($tvResponse->successful()) {
                $popularTvShows = $tvResponse->json()['results'] ?? [];
            } else {
                Log::warning('TMDB TV API returned non-success status', [
                    'status' => $tvResponse->status(),
                    'body'   => $tvResponse->body(),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Unexpected error fetching TV shows', ['message' => $e->getMessage()]);
        }

        // ✅ Fetch recommended movies based on user's favorites
        if (auth()->check()) {
            $favoriteMovieIds = auth()->user()->favorites()->pluck('movie_id');

            foreach ($favoriteMovieIds as $movieId) {
                try {
                    $recResponse = Http::timeout(5)
                        ->get("$baseUrl/movie/$movieId/recommendations", [
                            'api_key'  => $apiKey,
                            'language' => 'en-US',
                            'page'     => 1,
                        ]);

                    if ($recResponse->successful()) {
                        $recs = $recResponse->json()['results'] ?? [];
                        // Merge without duplicates
                        foreach ($recs as $rec) {
                            if (!collect($recommendedMovies)->pluck('id')->contains($rec['id'])) {
                                $recommendedMovies[] = $rec;
                            }
                        }
                    } else {
                        Log::warning("TMDB Recommendations API returned non-success for movie $movieId", [
                            'status' => $recResponse->status(),
                            'body' => $recResponse->body(),
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error("Unexpected error fetching recommendations for movie $movieId", [
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        return view('dashboard', [
            'popularMovies' => $popularMovies,
            'popularTvShows' => $popularTvShows,
            'recommendedMovies' => $recommendedMovies,
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
        $apiKey  = config('services.tmdb.api_key');
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
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                ]);
            }
        } catch (Exception $e) {
            // Log any unexpected error
            Log::error('Unexpected error fetching movie details', [
                'movie_id' => $id,
                'message'  => $e->getMessage(),
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
        $apiKey  = config('services.tmdb.api_key');
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
