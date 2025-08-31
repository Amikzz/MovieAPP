<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favourites;

class FavouritesController extends Controller
{
    // Toggle favorite status for a movie
    public function toggle(string $movieId, Request $request): JsonResponse
    {
        $user = Auth::user();

        // Check if already favorited
        $favorite = Favourites::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $favorited = false;
        } else {
            // Add to favorites
            Favourites::create([
                'user_id'  => $user->id,
                'movie_id' => $movieId,
            ]);
            $favorited = true;
        }

        return response()->json([
            'favorited' => $favorited,
        ]);
    }
}

