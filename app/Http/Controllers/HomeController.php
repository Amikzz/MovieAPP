<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // ✅ TMDB configuration
        $apiKey = config('services.tmdb.api_key');
        $baseUrl = config('services.tmdb.base_url');
        $imageUrl = config('services.tmdb.image_url');

        // ✅ Fetch popular movies from cache or TMDB
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
                Log::error('Unexpected error fetching movies', [
                    'message' => $e->getMessage(),
                ]);
                return [];
            }
        });

        // ✅ Fetch popular TV shows from cache or TMDB
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
                Log::error('Unexpected error fetching TV shows', [
                    'message' => $e->getMessage(),
                ]);
                return [];
            }
        });

        // ✅ Return Blade view
        return view('welcome', [
            'popularMovies' => $popularMovies,
            'popularTvShows' => $popularTvShows,
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
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
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
