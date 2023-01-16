<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SecondEmailRequest;
use App\Mail\VerifySecondaryMail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SecondaryEmailController extends Controller
{
    public function store(SecondEmailRequest $request)
    {
        $email = new Email();
        $email->user_id = $request->user_id;
        $email->email = $request->email;
        $email->token = Str::random(60);

        $email->save();
        Mail::to(jwtUser()->email)->send(new VerifySecondaryMail(jwtUser(), $email));
        return response()->json('Success', 200);
    }

    public function destroy(Email $email): JsonResponse
    {
        $email->delete();
        return response()->json('Email Deleted', 200);
    }

    public function verify(Request $request): JsonResponse
    {
        $email = Email::where('token', $request->token)->first();

        if ($email) {
            $email->email_verified_at = Carbon::now();
            $email->save();
        }
        return response()->json('Verified', 200);
    }

    public function change(Request $request): JsonResponse
    {
        $email = Email::where('id', $request->email_id)->first();
        $user = User::where('id', $request->user_id)->first();
        $user->email = $email->email;
        $user->save();
        $email->email = $request->email_body;
        $email->save();

        return response()->json('Success', 200);
    }
}
