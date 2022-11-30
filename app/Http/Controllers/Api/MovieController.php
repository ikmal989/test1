<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Movie_genre;
use Illuminate\Http\Request;
use App\Models\Movie_showtime;
use App\Models\Movie_performer;
use App\Http\Controllers\Controller;
use App\Models\Movie_rating;

class MovieController extends Controller
{
    // get genre movie
    public function genre(Request $request)
    {
        $movie = Movie::select(
                'movies.id as Movie_ID',
                'movies.title as Title',
                'movie_genres.genre as Genre',
                'movies.description as Description',
            )
            ->join('movie_genres', 'movie_genres.movie_id', '=', 'movies.id')
            ->where('movie_genres.genre', '=', $request->genre)
            ->get();

        return response()->json(
            [
                'data' => $movie
            ],
            200
        );
    }

    // get timeslot movie
    public function timeslot(Request $request)
    {
        $movie = Movie_showtime::select(
                'movies.id as Movie_ID',
                'movies.title as Title',
                'movies.theater_name as Theater_name',
                'movie_showtimes.show_start as Start_time',
                'movie_showtimes.end_start as End_time',
                'movies.description as Description',
                'movie_showtimes.theater_room_no as Theater_room_no',
            )
            ->join('movies', 'movie_showtimes.movie_id', '=', 'movies.id')
            ->where('movies.theater_name', '=', $request->theater_name)
            ->whereBetween('movie_showtimes.show_start', [$request->time_start, $request->time_end])
            ->get();

        return response()->json(
            [
                'data' => $movie
            ],
            200
        );
    }

    // get specific movie theater
    public function specific_movie_theater(Request $request)
    {
        $start_d_date = Carbon::parse($request->d_date)->format('Y-m-d H:i');
        $end_d_date = Carbon::parse($request->d_date)->addDay()->format('Y-m-d H:i');

        $movie = Movie_showtime::select(
                'movies.id as Movie_ID',
                'movies.title as Title',
                'movies.theater_name as Theater_name',
                'movie_showtimes.show_start as Start_time',
                'movie_showtimes.end_start as End_time',
                'movies.description as Description',
                'movie_showtimes.theater_room_no as Theater_room_no',
            )
            ->join('movies', 'movie_showtimes.movie_id', '=', 'movies.id')
            ->where('movies.theater_name', '=', $request->theater_name)
            ->whereBetween('movie_showtimes.show_start', [$start_d_date, $end_d_date])
            ->get();

        return response()->json(
            [
                'data' => $movie
            ],
            200
        );
    }

    // get search performer
    public function search_performer(Request $request)
    {
        $movie = Movie_performer::select(
                'movies.id as Movie_ID',
                'movies.overall_rating as Overall_rating',
                'movies.title as Title',
                'movies.description as Description',
            )
            ->join('movies', 'movie_performers.movie_id', '=', 'movies.id')
            ->where('movie_performers.performer_name', 'like', '%' . $request->performer_name . '%')
            ->get();

        return response()->json(
            [
                'data' => $movie
            ],
            200
        );
    }

    // post give rating
    public function give_rating(Request $request)
    {
        Movie_rating::create([
            'movie_title' => $request->movie_title,
            'username' => $request->username,
            'rating' => $request->rating,
            'r_description' => $request->r_description
        ]);

        return response()->json(
            [
                'message' => "Successfully added review for " . $request->movie_title . " by user: " . $request->username,
                'success' => true
            ],
            200
        );
    }

    // get new movies
    public function new_movies(Request $request)
    {
        $movie = Movie::select(
                'movies.id as Movie_ID',
                'movies.overall_rating as Overall_rating',
                'movies.title as Title',
                'movies.description as Description',
            )
            ->where('release', '=', $request->r_date)
            ->get();

        return response()->json(
            [
                'data' => $movie
            ],
            200
        );
    }

    // post add movie
    public function add_movie(Request $request)
    {
        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'director' => $request->director,
            'theater_name' => $request->theater_name,
            'release' => $request->release,
            'length' => $request->length,
            'mpaa_rating' => $request->mpaa_rating,
            'language' => $request->language,
        ]);

        foreach ($request->performer as $item)
        {
            Movie_performer::create([
                'movie_id' => $movie->id,
                'performer_name' => $item,
            ]);
        }

        foreach ($request->genre as $item)
        {
            Movie_genre::create([
                'movie_id' => $movie->id,
                'genre' => $item,
            ]);
        }

        return response()->json(
            [
                'message' => "Successfully added movie " . $request->title . " with Movie_ID " . $movie->id,
                'success' => true
            ],
            200
        );
    }
}
