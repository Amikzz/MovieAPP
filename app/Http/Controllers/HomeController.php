<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        // ✅ Get TMDB config from services.php
        $apiKey   = config('services.tmdb.api_key');
        $baseUrl  = config('services.tmdb.base_url');
        $imageUrl = config('services.tmdb.image_url');

        // ✅ Fetch Popular Movies
        $moviesResponse = Http::get("{$baseUrl}/movie/popular", [
            'api_key'  => $apiKey,
            'language' => 'en-US',
            'page'     => 1,
        ]);

        $popularMovies = $moviesResponse->json()['results'] ?? [];

        // ✅ Fetch Popular TV Shows
        $tvResponse = Http::get("{$baseUrl}/tv/popular", [
            'api_key'  => $apiKey,
            'language' => 'en-US',
            'page'     => 1,
        ]);

        $popularTvShows = $tvResponse->json()['results'] ?? [];

        // ✅ Pass image base URL also to Blade
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
