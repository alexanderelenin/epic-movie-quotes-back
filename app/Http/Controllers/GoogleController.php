<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleProviderCallback(): RedirectResponse
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $existingUser = User::where('email', $user->email)->get()->first();
            if ($existingUser) {

                auth()->login($existingUser, true);
            } else {

                $newUser = new User;
                $newUser->username = $user->name;
                $newUser->email = $user->email;
                $newUser->password = bcrypt($user->name);
                $newUser->thumbnail  = $user->avatar;
                $newUser->save();
                auth()->login($newUser, true);
                $payload = [
                    'exp' => Carbon::now()->addDay()->timestamp,
                    'uid' => User::where('email', '=', $user->email)->first()->id,
                ];

                $jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

                $cookie = cookie("access_token", $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

                return Redirect::to(env('FRONT_URL') . 'newsfeed')->withCookie($cookie);
            }

            $payload = [
                'exp' => Carbon::now()->addDay()->timestamp,
                'uid' => User::where('email', '=', $user->email)->first()->id,
            ];

            $jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

            $cookie = cookie("access_token", $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

            return Redirect::to(env('FRONT_URL') . 'newsfeed')->withCookie($cookie);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
