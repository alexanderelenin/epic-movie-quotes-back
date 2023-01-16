<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailController extends Controller
{
    public function index()
    {
    }

    public function verify(EmailVerificationRequest $request): JsonResponse
    {

        $request->fulfill();
        auth()->logout();

        return response()->json('Success', 200);
    }
}
