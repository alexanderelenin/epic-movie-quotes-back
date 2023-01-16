<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function store(CommentCreateRequest $request, Quote $quote): JsonResponse
    {
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->quote_id = $request->quote_id;
        $comment->user_id = $request->user_id;

        if (jwtUser()->id !== $request->quote_author) {
            $notification = new Notification();
            $notification->from = $request->user_id;
            $notification->to = $request->quote_author;
            $notification->type = $request->type;
            $notification->save();
            event(new NotificationEvent($request->all()));
        }
        event(new CommentEvent($request->body));
        $comment->save();

        return response()->json('Comment added', 200);
    }
}
