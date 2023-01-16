<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieCreateRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{

    public function index()
    {

        return response()->json(jwtUser()->movies->load('quotes'), 200);
    }
    public function get(Movie $movie): JsonResponse
    {
        $movie->genre = json_decode($movie->genre, true);

        if (jwtUser()->id !== $movie->user_id) {
            return response()->json('Not Found', 400);
        }
        return response()->json([$movie, 'quotes' => Quote::where('movie_id', $movie->id)->with('comments.author')->withCount('likes')->orderBy('created_at', 'desc')->get()], 200);
    }


    public function store(MovieCreateRequest $request): JsonResponse
    {
        $movie = new Movie();
        $movie->setTranslation('title', 'en', $request->title_en);
        $movie->setTranslation('title', 'ka', $request->title_ka);
        $movie->setTranslation('director', 'en', $request->director_en);
        $movie->setTranslation('director', 'ka', $request->director_ka);
        $movie->setTranslation('description', 'en', $request->description_en);
        $movie->setTranslation('description', 'ka', $request->description_ka);
        $movie->year = $request->year;
        $movie->genre = json_encode($request->genre);
        $movie->thumbnail = $request->file('thumbnail')->store('thumbnails');
        $movie->user_id = jwtUser()->id;
        $movie->save();


        return response()->json('Success', 200);
    }

    public function update(Movie $movie, MovieUpdateRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        if (isset($attributes['thumbnail'])) {
            $movie['thumbnail'] = $request->file('thumbnail')->store('thumbnails');
        }
        $movie->setTranslation('title', 'en', $request->title_en);
        $movie->setTranslation('title', 'ka', $request->title_ka);
        $movie->setTranslation('director', 'en', $request->director_en);
        $movie->setTranslation('director', 'ka', $request->director_ka);
        $movie->setTranslation('description', 'en', $request->description_en);
        $movie->setTranslation('description', 'ka', $request->description_ka);
        $movie->year = $request->year;
        $movie->genre = json_encode($request->genre);
        $movie->update();
        return response()->json('Movie updated!', 200);
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return response()->json('Movie Deleted', 200);
    }
}
