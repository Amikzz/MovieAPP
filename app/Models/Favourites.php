<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourites extends Model
{
    //Table name
    protected $table = 'favorites';

    protected $fillable = ['user_id', 'movie_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
