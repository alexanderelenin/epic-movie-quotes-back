<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = User::where('id', '=', jwtUser()->id)->first();
        $request->all();

        if (isset($request->username)) {
            $user->username = $request->username;
        }

        if (isset($request->password)) {
            $user->password = bcrypt($request->password);
        }

        if (isset($request->thumbnail)) {
            $user->thumbnail = config('app.url') . 'storage/' . $request->file('thumbnail')->store('thumbnails');
        }
        $user->save();

        return response()->json('Success', 200);
    }
}
