<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie_rating extends Model
{
    use HasFactory;

    protected $table = 'movie_ratings';
    protected $guarded = [];
}
