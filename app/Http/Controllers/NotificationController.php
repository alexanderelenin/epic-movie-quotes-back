<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function get(): JsonResponse
    {
        return response()->json(Notification::where('to', jwtUser()->id)->with('sender')->orderBy('created_at', 'desc')->get());
    }

    public function unread(): JsonResponse
    {
        return response()->json(Notification::where('to', '=', jwtUser()->id)->where('read', '=', 0)->orderBy('created_at', 'desc')->get());
    }

    public function mark(): JsonResponse
    {
        $notification = Notification::where('read', '=', 0)->where('to', jwtUser()->id);

        $notification->update(array('read' => 1));

        return response()->json('marked', 200);
    }
}
