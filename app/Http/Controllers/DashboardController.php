<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ TMDB configuration
        $apiKey   = config('services.tmdb.api_key');
        $baseUrl  = config('services.tmdb.base_url');
        $imageUrl = config('services.tmdb.image_url');

        // Initialize empty arrays in case of failure
        $popularMovies = [];
        $popularTvShows = [];

        try {
            // ✅ Fetch Popular Movies with timeout and error handling
            $moviesResponse = Http::timeout(5) // 5 seconds timeout
            ->get("$baseUrl/movie/popular", [
                'api_key'  => $apiKey,
                'language' => 'en-US',
                'page'     => 1,
            ]);

            // Check if request is successful
            if ($moviesResponse->successful()) {
                $popularMovies = $moviesResponse->json()['results'] ?? [];
            } else {
                // Log API error
                Log::warning('TMDB Movies API returned non-success status', [
                    'status' => $moviesResponse->status(),
                    'body'   => $moviesResponse->body(),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Unexpected error fetching movies', [
                'message' => $e->getMessage(),
            ]);
        }

        try {
            // ✅ Fetch Popular TV Shows with timeout and error handling
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
            Log::error('Unexpected error fetching TV shows', [
                'message' => $e->getMessage(),
            ]);
        }

        // ✅ Return Blade view with fallback empty arrays if any request failed
        return view('dashboard', [
            'popularMovies' => $popularMovies,
            'popularTvShows' => $popularTvShows,
            'imageUrl' => $imageUrl,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
