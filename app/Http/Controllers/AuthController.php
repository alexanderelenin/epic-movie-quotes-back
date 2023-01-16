<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthController extends Controller
{

	public function user(): JsonResponse
	{

		return response()->json(jwtUser(), 200);
	}
	public function register(CreateUserRequest $request)
	{
		$user = new User();
		$user->username = $request->username;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);

		$user->save();

		event(new Registered($user));

		return response()->json('Successfully registered', 200);
	}

	public function login(LoginRequest $request): JsonResponse
	{

		$email = $request->email;

		if ($email) {
		}
		$fieldName = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


		$authenticated = auth()->attempt(
			[$fieldName => $email, 'password' => $request->password]
		);
		$user = auth()->user();

		if (!$authenticated) {
			$email = Email::where('email', '=', $request->email)->first();
			if ($email) {
				if ($email->email_verified_at !== null) {
					$email = Email::where('email', '=', $request->email)->first()->user->email;
					$authenticated = auth()->attempt([
						'email' => $email,
						'password' => $request->password,
					]);
					$user = auth()->user();
				} else {
					return response()->json(['error' => "Email is not verified"], 200);
				}
			}
		}

		if (!$authenticated) {
			return response()->json(['error' => 'wrong email or password'], 401);
		}
		$remember = 1440;
		if ($user->email_verified_at === null) {

			return response()->json(['error' => 'Email is not verified'], 404);
		}

		if ($request->remember == 'yes') {
			$remember = 43000;
		}

		$payload = [
			'exp' => Carbon::now()->addHours(4)->timestamp,
			'uid' => auth()->user()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie("access_token", $jwt, $remember, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie("access_token", '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user' => jwtUser()->load('emails')
			],
			200
		);
	}
}
