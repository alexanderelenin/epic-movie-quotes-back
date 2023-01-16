<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function like(Like $like, Request $request): JsonResponse
    {
        $like = Like::where('user_id', '=', $request->user_id)->where('quote_id', '=', $request->quote_id)->first();



        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $request->user_id,
                'quote_id' => $request->quote_id
            ]);
            if (jwtUser()->id !== $request->quote_author) {
                $notification = new Notification();
                $notification->from = $request->user_id;
                $notification->to = $request->quote_author;
                $notification->type = $request->type;
                $notification->save();
                event(new NotificationEvent($request->all()));
            }
        }



        event(new LikeEvent($request->all()));

        return response()->json('Liked', 200);
    }
}
