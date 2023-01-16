<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordController extends Controller
{


    public function validateEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? response()->json($status, 200)
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset($token): View | RedirectResponse
    {
        $email = request('email');
        return redirect(env('FRONT_URL') . "?token={$token}&email={$email}");
    }

    public function update(UpdatePasswordRequest $request): JsonResponse
    {
        $attributes = [
            'token' => $request->token,
            'email' => $request->email,
            'password' => $request->password,
        ];


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );


        return $status === Password::PASSWORD_RESET
            ? response()->json($status, 200)
            : back()->withErrors(['email' => [__($status)]]);
    }
}
