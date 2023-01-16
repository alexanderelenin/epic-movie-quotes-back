<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\QuoteCreateRequest;
use App\Http\Requests\QuoteUpdateRequest;
use App\Http\Requests\SearchQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{

    public function index()
    {
        return response()->json(Quote::with('author')->with('movie')->with('comments.author')->withCount('likes')->with('likes.author')->orderBy('created_at', 'desc')->paginate(2), 200);
    }
    public function store(QuoteCreateRequest $request)
    {

        $quote = new Quote();
        $quote->setTranslation('quote', 'en', $request->quote_en);
        $quote->setTranslation('quote', 'ka', $request->quote_ka);
        $quote->thumbnail = $request->file('thumbnail')->store('thumbnails');
        $quote->movie_id = $request->movie_id;
        $quote->user_id = jwtUser()->id;
        $quote->save();

        return response()->json('Quote added', 200);
    }
    public function destroy(Quote $quote)
    {
        $quote->delete();
        return response()->json('Quote deleted', 200);
    }

    public function get(Quote $quote): JsonResponse
    {

        return response()->json($quote->load('author')->load('comments.author')->loadCount('likes'), 200);
    }

    public function update(Quote $quote, QuoteUpdateRequest $request)
    {


        if (isset($request['thumbnail'])) {
            $quote['thumbnail'] = $request->file('thumbnail')->store('thumbnails');
        }

        $quote->setTranslation('quote', 'en', $request->quote_en);
        $quote->setTranslation('quote', 'ka', $request->quote_ka);
        $quote->update();
        return response()->json('Quote updated!', 200);
    }


    public function search(SearchQuoteRequest $request): JsonResponse
    {
        $quotes = [];
        $search = $request->search;
        if ($search[0] == '@') {
            $search = ltrim($search, '@');
            $quotes = Quote::whereHas('movie', function ($query) use ($search) {
                $query
                    ->where('title->en', 'like', $search . '%')
                    ->orWhere('title->ka', 'like', $search . '%');
            })->with('author')->with('movie')->with('comments.author')->withCount('likes')->with('likes.author')->orderBy('created_at', 'desc')->get();
        } elseif ($search[0] == '#') {
            $search = ltrim($search, '#');
            $quotes = Quote::query()
                ->where('quote->en', 'like', '%' . $search . '%')->with('author')->with('movie')->with('comments.author')->withCount('likes')->with('likes.author')->orderBy('created_at', 'desc')
                ->orWhere('quote->ka', 'like', '%' . $search . '%')->with('author')->with('movie')->with('comments.author')->withCount('likes')->with('likes.author')->orderBy('created_at', 'desc')
                ->get();
        } else {
            $quotes = Quote::whereHas('movie', function ($query) use ($search) {
                $query
                    ->where('title->en', 'like', $search . '%')->orWhere('title->ka', 'like', $search . '%');
            })->orwhere('quote->en', 'like', '%' . $search . '%')
                ->orwhere('quote->ka', 'like', '%' . $search . '%')->get();
        }
        return response()->json($quotes);
    }

    public function refresh(Request $request): JsonResponse
    {
        return response()->json(Quote::take($request->number)->with('author')->with('movie')->with('comments.author')->withCount('likes')->with('likes.author')->orderBy('created_at', 'desc')->get());
    }
}
