<?php

namespace App\Http\Controllers;

use App\Models\Favourites;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouritesController extends Controller
{
    // Toggle favorite status for a movie
    public function toggle(Request $request, string $type, string $itemId): JsonResponse
    {
        $user = Auth::user();

        $favorite = Favourites::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->where('type', $type)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
        } else {
            Favourites::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
                'type' => $type,
            ]);
            $favorited = true;
        }

        return response()->json([
            'favorited' => $favorited,
        ]);
    }
}

