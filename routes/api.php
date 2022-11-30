<?php

use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(array('middleware' => ['custom_auth']), function ()
{
    Route::apiResource('token', TokenController::class);
    Route::post('/token/topup', [TokenController::class, 'store']);
});

Route::get('/genre', [MovieController::class, 'genre']);
Route::get('/timeslot', [MovieController::class, 'timeslot']);
Route::get('/specific_movie_theater', [MovieController::class, 'specific_movie_theater']);
Route::get('/search_performer', [MovieController::class, 'search_performer']);
Route::post('/give_rating', [MovieController::class, 'give_rating']);
Route::get('/new_movies', [MovieController::class, 'new_movies']);
Route::post('/add_movie', [MovieController::class, 'add_movie']);




